#Phaseolus case study
#Julian Ramirez-Villegas
#CIAT
#March 2012
stop("Warning: do not run the whole thing")

#basic stuff
src.dir <- "D:/_tools/gap-analysis-cwr/trunk/gap-analysis/lathyrus"

#crop details
crop_dir <- "D:/CIAT_work/Gap_analysis/ICARDA-collab/lathyrus"; setwd(crop_dir)

#here first run the occurrence splitter (H/G) and the script to count and plot records

#extract climate data
source(paste(src.dir,"/001.extractClimates.R",sep=""))

occ_dir <- paste(crop_dir,"/occurrences",sep="")
cli_dir <- "D:/CIAT_work/climate_change/wcl_2_5min/bio"
swd_dir <- paste(crop_dir,"/swd",sep="")
if (!file.exists(swd_dir)) {dir.create(swd_dir)}

x <- extractClimates(input_dir=occ_dir,sample_file="lathyrus.csv",env_dir=cli_dir,
                     env_prefix="bio_",env_ext="",lonfield="lon",
                     latfield="lat",taxfield="Taxon",output_dir=swd_dir)


#splitting the occurrence files
source(paste(src.dir,"/003.createOccurrenceFiles.R",sep=""))
oDir <- paste(crop_dir,"/maxent_modelling/occurrence_files",sep="")
if (!file.exists(oDir)) {dir.create(oDir)}
x <- createOccFiles(occ=paste(crop_dir,"/swd/occurrences_swd_ok.csv",sep=""),taxfield="Taxon",outDir=oDir)


#making the pseudo-absences
source(paste(src.dir,"/002.selectBackgroundArea.R",sep=""))
fList <- list.files("./maxent_modelling/occurrence_files",pattern=".csv")

bkDir <- paste(crop_dir,"/maxent_modelling/background",sep="")
if (!file.exists(bkDir)) {dir.create(bkDir)}

for (f in fList) {
  cat("Processing",paste(f),"\n")
  iFile <- paste("./maxent_modelling/occurrence_files/",f,sep="")
  oFile <- paste("./maxent_modelling/background/",f,sep="")
  x <- selectBack(occFile=iFile, outBackName=oFile, 
                  msk="D:/CIAT_work/GBIF_project/backgroundFiles/backselection.asc", 
                  backFilesDir="D:/CIAT_work/GBIF_project/backgroundFiles")
}


#perform the maxent modelling in parallel
source(paste(src.dir,"/005.modelingApproach.R",sep=""))
GapProcess(inputDir=paste(crop_dir,"/maxent_modelling",sep=""), OSys="NT", ncpu=3)


#summarise the metrics
source(paste(src.dir,"/006.summarizeMetricsThresholds.R",sep=""))
x <- summarizeMetrics(idir=paste(crop_dir,"/maxent_modelling",sep=""))


#calculate area with SD<0.15 (aSD15)
source(paste(src.dir,"/007.calcASD15.R",sep=""))
x <- summarizeASD15(idir=paste(crop_dir,"/maxent_modelling",sep=""))


#calculate size of distributional range
source(paste(src.dir,"/008.sizeDR.R",sep=""))
x <- summarizeDR(crop_dir)


#select which taxa are of use for species richness
#get the following modelling metrics:
# a. 25-fold average test AUC (ATAUC)
# b. 25-fold stdev of test AUC (STAUC)
# c. proportion of potential distribution with SD>15 (ASD15)

#isValid==1 if ATAUC>0.7, STAUC<0.15, ASD15<10%
acc <- read.csv(paste(crop_dir,"/maxent_modelling/summary-files/accuracy.csv",sep=""))
asd <- read.csv(paste(crop_dir,"/maxent_modelling/summary-files/ASD15.csv",sep=""))

for (spp in acc$SPID) {
  cat("Processing taxon",paste(spp),"\n")
  
  #getting the quality metrics
  atauc <- acc$TestAUC[which(acc$SPID==spp)]
  stauc <- acc$TestAUCSD[which(acc$SPID==spp)]
  asd15 <- asd$rateThresholded[which(asd$taxon==paste(spp))]
  
  #putting everything onto a row for appending
  row_res <- data.frame(Taxon=paste(spp),ATAUC=atauc,STAUC=stauc,ASD15=asd15,ValidModel=NA)
  
  #checking if any is na and correcting consequently
  if (is.na(atauc)) {atauc <- 0}
  if (is.na(stauc)) {stauc <- 1}
  if (is.na(asd15)) {asd15 <- 100}
  
  #reporting model quality
  if (atauc>=0.7 & stauc<=0.15 & asd15<=10) {
    row_res$ValidModel <- 1
  } else {
    row_res$ValidModel <- 0
  }
  
  #appending everything
  if (spp == acc$SPID[1]) {
    res_all <- row_res
  } else {
    res_all <- rbind(res_all,row_res)
  }
  
}
write.csv(res_all,paste(crop_dir,"/maxent_modelling/summary-files/taxaForRichness.csv",sep=""),quote=F,row.names=F)


#calculate species richness
source(paste(src.dir,"/010.speciesRichness.R",sep=""))
x <- speciesRichness(bdir=crop_dir)


#create the priorities table
#1. SRS=GS/(GS+HS)*10
table_base <- read.csv(paste(crop_dir,"/sample_counts/sample_count_table.csv",sep=""))
table_base <- data.frame(Taxon=table_base$TAXON)
table_base$HS <- NA; table_base$HS_RP <- NA
table_base$GS <- NA; table_base$GS_RP <- NA
table_base$TOTAL <- NA; table_base$TOTAL_RP <- NA
table_base$ATAUC <- NA; table_base$STAUC <- NA; table_base$ASD15 <- NA; table_base$IS_VALID <- NA
table_base$SRS <- NA; table_base$CA50_G <- NA; table_base$PD_COV <- NA
table_base$GRS <- NA; table_base$NC_G_PC1 <- NA; table_base$NC_PD_PC1 <- NA
table_base$NC_G_PC2 <- NA; table_base$NC_PD_PC2 <- NA; table_base$ERS <- NA
table_base$ERTS <- NA; table_base$FPS <- NA; table_base$FPCAT <- NA

#reading specific tables
samples <- read.csv(paste(crop_dir,"/sample_counts/sample_count_table.csv",sep=""))
model_met <- read.csv(paste(crop_dir,"/maxent_modelling/summary-files/taxaForRichness.csv",sep=""))
rsize <- read.csv(paste(crop_dir,"/maxent_modelling/summary-files/areas.csv",sep=""))
edist <- read.csv(paste(crop_dir,"/maxent_modelling/summary-files/edist.csv",sep=""))

#read principal components weights and scale them to match 1
#!!!!!!
w_pc1 <- 0.7
w_pc2 <- 0.3

for (spp in table_base$Taxon) {
  cat("Processing species",paste(spp),"\n")
  
  #sampling and SRS
  hs <- samples$HNUM[which(samples$TAXON==paste(spp))]
  hs_rp <- samples$HNUM_RP[which(samples$TAXON==paste(spp))]
  gs <- samples$GNUM[which(samples$TAXON==paste(spp))]
  gs_rp <- samples$GNUM_RP[which(samples$TAXON==paste(spp))]
  total <- samples$TOTAL[which(samples$TAXON==paste(spp))]
  total_rp <- samples$TOTAL_RP[which(samples$TAXON==paste(spp))]
  srs <- gs/total*10
  
  table_base$HS[which(table_base$Taxon==paste(spp))] <- hs
  table_base$HS_RP[which(table_base$Taxon==paste(spp))] <- hs_rp
  table_base$GS[which(table_base$Taxon==paste(spp))] <- gs
  table_base$GS_RP[which(table_base$Taxon==paste(spp))] <- gs_rp
  table_base$TOTAL[which(table_base$Taxon==paste(spp))] <- total
  table_base$TOTAL_RP[which(table_base$Taxon==paste(spp))] <- total_rp
  table_base$SRS[which(table_base$Taxon==paste(spp))] <- srs
  
  
  #modelling metrics
  atauc <- model_met$ATAUC[which(model_met$Taxon==paste(spp))]
  stauc <- model_met$STAUC[which(model_met$Taxon==paste(spp))]
  asd15 <- model_met$ASD15[which(model_met$Taxon==paste(spp))]
  isval <- model_met$ValidModel[which(model_met$Taxon==paste(spp))]
  
  table_base$ATAUC[which(table_base$Taxon==paste(spp))] <- atauc
  table_base$STAUC[which(table_base$Taxon==paste(spp))] <- stauc
  table_base$ASD15[which(table_base$Taxon==paste(spp))] <- asd15
  table_base$IS_VALID[which(table_base$Taxon==paste(spp))] <- isval
  
  #grs
  g_ca50 <- rsize$GBSize[which(rsize$taxon==paste(spp))]
  
  if (isval==1) {
    drsize <- rsize$DRSize[which(rsize$taxon==paste(spp))]
  } else {
    drsize <- rsize$CHSize[which(rsize$taxon==paste(spp))]
  }
  
  grs <- g_ca50/drsize*10
  if (!is.na(grs)) {
    if (grs>10) {grs <- 10}
  }
  table_base$CA50_G[which(table_base$Taxon==paste(spp))] <- g_ca50
  table_base$PD_COV[which(table_base$Taxon==paste(spp))] <- drsize
  table_base$GRS[which(table_base$Taxon==paste(spp))] <- grs
  
  #ers
  ecg_ca50_pc1 <- edist$GBDist.PC1[which(edist$taxon==paste(spp))]
  ecg_ca50_pc2 <- edist$GBDist.PC2[which(edist$taxon==paste(spp))]
  
  dr_pc1 <- edist$DRDist.PC1[which(edist$taxon==paste(spp))]
  dr_pc2 <- edist$DRDist.PC2[which(edist$taxon==paste(spp))]
  
  ers_pc1 <- ecg_ca50_pc1/dr_pc1*10
  if (!is.na(ers_pc1)) {
    if (ers_pc1 > 10) {ers_pc1 <- 10}
  }
  ers_pc2 <- ecg_ca50_pc2/dr_pc2*10
  if (!is.na(ers_pc2)) {
    if (ers_pc2 > 10) {ers_pc2 <- 10}
  }
  
  ers <- ers_pc1*w_pc1 + ers_pc2*w_pc2
  if (!is.na(ers))
  if (ers > 10) {ers <- 10}
  
  table_base$NC_G_PC1[which(table_base$Taxon==paste(spp))] <- ecg_ca50_pc1
  table_base$NC_PD_PC1[which(table_base$Taxon==paste(spp))] <- dr_pc1
  table_base$NC_G_PC2[which(table_base$Taxon==paste(spp))] <- ecg_ca50_pc2
  table_base$NC_PD_PC2[which(table_base$Taxon==paste(spp))] <- dr_pc2
  table_base$ERS[which(table_base$Taxon==paste(spp))] <- ers
  
  #Final priority score
  if (gs==0) {
    fps <- 0
  } else if (hs==0 & gs<10) {
    fps <- 0
  } else {
    fps <- mean(c(srs,grs,ers),na.rm=T)
  }
  table_base$FPS[which(table_base$Taxon==paste(spp))] <- fps
  
  if (fps>=0 & fps<=3) {
    fpcat <- "HPS"
  } else if (fps>3 & fps<=5) {
    fpcat <- "MPS"
  } else if (fps>5 & fps<=7.5) {
    fpcat <- "LPS"
  } else {
    fpcat <- "NFCR"
  }
  table_base$FPCAT[which(table_base$Taxon==paste(spp))] <- fpcat
}

if (!file.exists(paste(crop_dir,"/priorities",sep=""))) {
  dir.create(paste(crop_dir,"/priorities",sep=""))
}
write.csv(table_base,paste(crop_dir,"/priorities/priorities.csv",sep=""),row.names=F,quote=F)

#sub-select hps
table_hps <- table_base[which(table_base$FPCAT=="HPS"),]
write.csv(table_hps,paste(crop_dir,"/priorities/hps.csv",sep=""),row.names=F,quote=F)


#calculate distance to populations
source(paste(src.dir,"/011.distanceToPopulations.R",sep=""))
summarizeDistances(crop_dir)

#calculate final gap richness
source(paste(src.dir,"/012.gapRichness.R",sep=""))
x <- gapRichness(bdir=crop_dir)


#plot the CA50 vs Potential coverage thing
prior <- read.csv(paste(crop_dir,"/priorities/priorities.csv",sep=""))

fit <- lm(prior$CA50_G~prior$PD_COV)
lims <- c(min(prior$PD_COV,prior$CA50_G),max(prior$CA50_G,prior$PD_COV))/1000

#do the plot
tiff(paste(crop_dir,"/figures/geographic_coverage.tif",sep=""),
         res=300,pointsize=12,width=1500,height=1000,units="px",compression="lzw")
par(mar=c(5,5,1,1),cex=0.8)
plot(prior$PD_COV/1000,prior$CA50_G/1000,pch=20,cex=0.75,xlim=lims,ylim=c(0,1000),
     xlab="Potential geographic coverage (sq-km * 1000)",
     ylab="Genebank accessions CA50 (sq-km * 1000")
abline(0,1,lwd=0.75,lty=2)
lines(prior$PD_COV/1000,fit$fitted.values/1000)
grid(lwd=0.75)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_sativus")]/1000+2000,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_sativus")]/1000,
     "L. sativus",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_cicera")]/1000+1500,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_cicera")]/1000,
     "L. cicera",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_aphaca")]/1000+2000,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_aphaca")]/1000,
     "L. aphaca",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_pratensis")]/1000-2000,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_pratensis")]/1000,
     "L. pratensis",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_ochrus")]/1000+1500,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_ochrus")]/1000,
     "L. ochrus",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_clymenum")]/1000+2000,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_clymenum")]/1000,
     "L. clymenum",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_inconspicuus")]/1000+2500,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_inconspicuus")]/1000,
     "L. inconspicuus",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_annuus")]/1000+1500,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_annuus")]/1000,
     "L. annuus",cex=0.5)
text(prior$PD_COV[which(prior$Taxon=="Lathyrus_pseudocicera")]/1000+2400,
     prior$CA50_G[which(prior$Taxon=="Lathyrus_pseudocicera")]/1000-10,
     "L. pseudocicera",cex=0.5)
dev.off()


#plot the gap richness maps, uncertainty and related stuff
source(paste(src.dir,"/000.zipRead.R",sep=""))

gap_rich <- zipRead(paste(crop_dir,"/gap_richness/",sep=""),"gap-richness.asc.gz")
gap_dpmax <- zipRead(paste(crop_dir,"/gap_richness/",sep=""),"gap-richness-dpmax.asc.gz")
gap_sdmax <- zipRead(paste(crop_dir,"/gap_richness/",sep=""),"gap-richness-sdmax.asc.gz")

library(maptools); data(wrld_simpl)

z <- extent(gap_rich)
aspect <- (z@ymax-z@ymin)*1.2/(z@xmax-z@xmin)

grich_brks <- unique(gap_rich[!is.na(gap_rich[])])
grich_cols <- c("grey 80",colorRampPalette(c("yellow","orange","red"))(length(grich_brks)-2))

#gap richness map
tiff(paste(crop_dir,"/figures/gap_richness.tif",sep=""),
         res=300,pointsize=7,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8)
plot(gap_rich,col=grich_cols,zlim=c(min(grich_brks),max(grich_brks)),useRaster=F,
     breaks=grich_brks,lab.breaks=grich_brks,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5)
grid()
dev.off()


###############
#gsd_brks <- unique(quantile(gap_sdmax[],probs=seq(0,1,by=0.05),na.rm=T))
gap_sdmax[which(gap_rich[]==0)] <- NA
gsd_brks <- c(seq(0,max(gap_sdmax[],na.rm=T),by=0.05),max(gap_sdmax[],na.rm=T))
gsd_cols <- colorRampPalette(c("light green","green","light blue","blue"))(length(gsd_brks)-1)
gsd_labs <- round(gsd_brks,2)

#gap uncertainty map (standard deviation)
tiff(paste(crop_dir,"/figures/gap_richness_sd.tif",sep=""),
         res=300,pointsize=7,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8)
plot(gap_sdmax,col=gsd_cols,zlim=c(min(gsd_brks),max(gsd_brks)),useRaster=F,
     breaks=gsd_brks,lab.breaks=gsd_labs,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5)
grid()
dev.off()


gap_dpmax[which(gap_rich[]==0)] <- NA
gdp_brks <- unique(quantile(gap_dpmax[],probs=seq(0,1,by=0.05),na.rm=T))
gdp_cols <- colorRampPalette(c("yellow","green","blue"))(length(gdp_brks)-1)
gdp_labs <- round(gdp_brks,2)


#gap uncertainty map (popdist)
tiff(paste(crop_dir,"/figures/gap_richness_dp.tif",sep=""),
         res=300,pointsize=7,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8)
plot(gap_dpmax,col=gdp_cols,zlim=c(min(gdp_brks),max(gdp_brks)),useRaster=F,
     breaks=gdp_brks,lab.breaks=gdp_labs,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5)
grid()
dev.off()


#comparison of expert and gap scores
eps <- read.csv(paste(crop_dir,"/priorities/expert_gap_comparison.csv",sep=""))
spear <- cor(eps$FPS,eps$EPS,method="spearman")
eps$RD <- (eps$FPS-eps$EPS)*10

fit <- lm(eps$EPS~eps$FPS)


tiff(paste(crop_dir,"/figures/expert_evaluation.tif",sep=""),
         res=300,pointsize=12,width=1500,height=1000,units="px",compression="lzw")
par(mar=c(5,5,1,1),cex=0.8)
plot(eps$FPS,eps$EPS,xlab="Gap Analysis Final priority score",
     ylab="Expert priority score",pch=20,xlim=c(0,8),ylim=c(0,8))
lines(eps$FPS,fit$fitted.values)
abline(0,1,lty=2)
grid()
dev.off()


tiff(paste(crop_dir,"/figures/expert_evaluation_RD.tif",sep=""),
         res=300,pointsize=12,width=1500,height=1000,units="px",compression="lzw")
par(mar=c(5,5,1,1),cex=0.8)
hist(eps$RD,xlab="Relative difference (%)",
     ylab="Frequency (number of taxa)",
     breaks=20,xlim=c(-100,100),col="grey 70",main=NA)
abline(v=0,col="red")
grid()
dev.off()


#plot species richness
source(paste(src.dir,"/000.zipRead.R",sep=""))
sp_rich <- zipRead(paste(crop_dir,"/species_richness",sep=""),"species-richness.asc.gz")
sdmax <- zipRead(paste(crop_dir,"/species_richness",sep=""),"species-richness-sdmax.asc.gz")


library(maptools); data(wrld_simpl)

z <- extent(sp_rich)
aspect <- (z@ymax-z@ymin)*1.2/(z@xmax-z@xmin)

rich_brks <- unique(sp_rich[!is.na(sp_rich[])])
rich_cols <- c("grey 80",colorRampPalette(c("yellow","orange","red"))(length(rich_brks)-2))

#gap richness map
tiff(paste(crop_dir,"/figures/sp_richness.tif",sep=""),
         res=300,pointsize=7,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8)
plot(sp_rich,col=rich_cols,zlim=c(min(rich_brks),max(rich_brks)),useRaster=F,
     breaks=rich_brks,lab.breaks=rich_brks,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5)
grid()
dev.off()


###############
#gsd_brks <- unique(quantile(gap_sdmax[],probs=seq(0,1,by=0.05),na.rm=T))
sdmax[which(sp_rich[]==0)] <- NA
sd_brks <- c(seq(0,max(sdmax[],na.rm=T),by=0.05),max(sdmax[],na.rm=T))
sd_cols <- colorRampPalette(c("light green","green","light blue","blue"))(length(sd_brks)-1)
sd_labs <- round(sd_brks,2)


#gap uncertainty map (standard deviation)
tiff(paste(crop_dir,"/figures/sp_richness_sd.tif",sep=""),
         res=300,pointsize=7,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8)
plot(gap_sdmax,col=sd_cols,zlim=c(min(sd_brks),max(sd_brks)),useRaster=F,
     breaks=sd_brks,lab.breaks=sd_labs,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5)
grid()
dev.off()



