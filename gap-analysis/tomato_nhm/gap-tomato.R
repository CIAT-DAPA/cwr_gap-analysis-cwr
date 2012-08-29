#Gap analysis workshop
#Based on Phaseolus study (Ramírez-Villegas, J.)
#J. Ramirez 
#CIAT
#March 2012
stop("Warning: do not run the whole thing")

#basic stuff - where is the code
src.dir <- "/curie_data2/ncastaneda/code/gap-analysis-cwr/gap-analysis/tomato_nhm"
gap.dir <-"/curie_data2/ncastaneda/gap-analysis"

#crop details
crop <- "tomato_nhm"
crop_dir <- paste(gap.dir,"/gap_",crop,sep="")
setwd(crop_dir)

#basic stuff - creating folders
mxnt <- paste(crop_dir,"/maxent_modeling",sep="")
if (!file.exists(mxnt)) {dir.create(mxnt)}

# !!!!TO FIX: include path for creating background points

#== split H/G occurrences ==#
occ <- read.csv(paste("./occurrences/",crop,".csv",sep=""))
h <- occ[which(occ$H==1),]
g <- occ[which(occ$G==1),]

write.csv(h,paste("./occurrences/",crop,"_h.csv",sep=""),quote=F,row.names=F)
write.csv(g,paste("./occurrences/",crop,"_g.csv",sep=""),quote=F,row.names=F)

#== samples densities comparison ==#
source(paste(src.dir,"/01.splitHG.R",sep=""))

#== compare germplasm vs. total records==#
source(paste(src.dir,"/02.countRecords.R",sep=""))

#== extract climate data ==#
source(paste(src.dir,"/001.extractClimates.R",sep=""))

occ_dir <- paste(crop_dir,"/occurrences",sep="")
#set climate dir
cli_dir <- "/curie_data2/ncastaneda/geodata/bio_30s_sa"
swd_dir <- paste(crop_dir,"/swd",sep="")
if (!file.exists(swd_dir)) {dir.create(swd_dir)}

sample_file = paste(crop,".csv", sep="")

x <- extractClimates(input_dir=occ_dir,sample_file=sample_file,env_dir=cli_dir,
                     env_prefix="bio_",env_ext=".asc",lonfield="lon",
                     latfield="lat",taxfield="Taxon",output_dir=swd_dir)

#== splitting the occurrence files ==#
source(paste(src.dir,"/003.createOccurrenceFiles.R",sep=""))
oDir <- paste(crop_dir,"/maxent_modeling/occurrence_files",sep="")
if (!file.exists(oDir)) {dir.create(oDir)}
x <- createOccFiles(occ=paste(crop_dir,"/swd/occurrences_swd_ok.csv",sep=""),taxfield="Taxon",outDir=oDir)

#== making the pseudo-absences ==#
source(paste(src.dir,"/002.selectBackgroundArea.R",sep=""))
fList <- list.files("./maxent_modeling/occurrence_files",pattern=".csv")

bkDir <- paste(crop_dir,"/maxent_modeling/background",sep="")
if (!file.exists(bkDir)) {dir.create(bkDir)}

for (f in fList) {
  cat("Processing",paste(f),"\n")
  iFile <- paste("./maxent_modeling/occurrence_files/",f,sep="")
  oFile <- paste("./maxent_modeling/background/",f,sep="")
  x <- selectBack(occFile=iFile, outBackName=oFile, 
                  msk=paste(gap.dir,"/_backgroundFiles_alt/backselection.asc",sep=""), 
                  backFilesDir=paste(gap.dir,"/_backgroundFiles_alt/",sep=""))
}

#== prepare native areas grids ==#
source(paste(src.dir,"/01.splitHG.R",sep="")) # OJO UPDATE ACCORDINGLY!!!!
mxnt <- paste(crop_dir,"/maxent_modeling",sep=""); if (!file.exists(mxnt)) {dir.create(mxnt)}

#== prepare cellArea grid ==#
rs <- raster("./masks/mask.asc")
rs_a <- area(rs)
rs_a <- mask(rs_a, rs)
writeRaster(rs_a,"./masks/cellArea.asc",overwrite="TRUE")

#== perform the maxent modelling in parallel ==#
source(paste(src.dir,"/005.modelingApproach.R",sep=""))
#source(paste(src.dir,"/_005.modelingApproach_original.R",sep=""))
GapProcess(inputDir=paste(crop_dir,"/maxent_modeling",sep=""), OSys="linux", ncpu=5)

#== summarise the metrics ==#
source(paste(src.dir,"/006.summarizeMetricsThresholds.R",sep=""))
x <- summarizeMetrics(idir=paste(crop_dir,"/maxent_modeling",sep=""))

#== calculate area with SD<0.15 (aSD15) ==#
source(paste(src.dir,"/007.calcASD15.R",sep=""))
x <- summarizeASD15(idir=paste(crop_dir,"/maxent_modeling",sep=""))

#== calculate size of distributional range ==#

#Create cell area file
rs <- paste(crop_dir, "/maxent_modeling/masks/mask.asc", sep="")
rs_a <- area(rs)
rs_a <- mask(rs_a,rs)
writeRaster(rs_a,paste(crop_dir, "/maxent_modeling/masks/cellArea.asc",sep=""),overwrite="TRUE")

source(paste(src.dir,"/008.sizeDR.R",sep=""))
x <- summarizeDR(crop_dir)

#== calculate environmental distance of distributional range ==#
source(paste(src.dir,"/009.edistDR.R",sep=""))
x <- summarizeDR_env(crop_dir)

#select which taxa are of use for species richness
#get the following modelling metrics:
# a. 25-fold average test AUC (ATAUC)
# b. 25-fold stdev of test AUC (STAUC)
# c. proportion of potential distribution with SD>15 (ASD15)

#== isValid==1 if ATAUC>0.7, STAUC<0.15, ASD15<10% ==#
acc <- read.csv(paste(crop_dir,"/maxent_modeling/summary-files/accuracy.csv",sep=""))
asd <- read.csv(paste(crop_dir,"/maxent_modeling/summary-files/ASD15.csv",sep=""))

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
write.csv(res_all,paste(crop_dir,"/maxent_modeling/summary-files/taxaForRichness.csv",sep=""),quote=F,row.names=F)

#== calculate species richness ==#
source(paste(src.dir,"/010.speciesRichness.R",sep=""))
x <- speciesRichness(bdir=crop_dir)

#== create the priorities table ==#
#1. SRS=GS/(GS+HS)*10
table_base <- read.csv(paste(crop_dir,"/sample_counts/sample_count_table.csv",sep=""))
table_base <- data.frame(Taxon=table_base$TAXON)
table_base$HS <- NA; table_base$HS_RP <- NA
table_base$GS <- NA; table_base$GS_RP <- NA
table_base$TOTAL <- NA; table_base$TOTAL_RP <- NA
table_base$ATAUC <- NA; table_base$STAUC <- NA; table_base$ASD15 <- NA; table_base$IS_VALID <- NA
table_base$SRS <- NA; table_base$GRS <- NA; table_base$ERS <- NA
table_base$ERTS <- NA; table_base$FPS <- NA; table_base$FPCAT <- NA

#== reading specific tables ==#
samples <- read.csv(paste(crop_dir,"/sample_counts/sample_count_table.csv",sep=""))
model_met <- read.csv(paste(crop_dir,"/maxent_modeling/summary-files/taxaForRichness.csv",sep=""))
rsize <- read.csv(paste(crop_dir,"/maxent_modeling/summary-files/areas.csv",sep=""))
edist <- read.csv(paste(crop_dir,"/maxent_modeling/summary-files/edist.csv",sep=""))

#== read principal components weights and scale them to match 1 ==#
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

#== sub-select hps ==#
table_hps <- table_base[which(table_base$FPCAT=="HPS"),]
write.csv(table_hps,paste(crop_dir,"/priorities/hps.csv",sep=""),row.names=F,quote=F)

#== calculate distance to populations ==#
source(paste(src.dir,"/011.distanceToPopulations.R",sep=""))
summarizeDistances(crop_dir)

#== calculate final gap richness ==#
source(paste(src.dir,"/012.gapRichness.R",sep=""))
x <- gapRichness(crop_dir)

#== prepare the graphs ==#
#sources(paste(src/dir,"/000.preparingGraphs.R", sep=""))
