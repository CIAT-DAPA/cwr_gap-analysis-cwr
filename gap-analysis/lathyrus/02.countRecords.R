#ICARDA and CIAT Collaboration on gap analysis
#March 2012
stop("")

wd <- "E:/CIAT"
setwd(wd)

#read occurrences
occ <- read.csv("./occurrences/lathyrus.csv")
taxField <- "Taxon"
hField <- "H"
gField <- "G"

#taxon unique values
taxNames <- unique(occ[,taxField])

for (tax in taxNames) {
  cat("Counting",paste(tax),"\n")
  
  #subselect the data for this taxon
  taxData <- occ[which(occ[,taxField]==tax),]
  
  #count herbarium recs and germplasm samples
  hc <- sum(taxData[,hField])
  gc <- sum(taxData[,gField])
  
  row_out <- data.frame(TAXON=paste(tax),HNUM=hc,GNUM=gc)
  
  if (tax==taxNames[1]) {
    sampAll <- row_out
  } else {
    sampAll <- rbind(sampAll,row_out)
  }
}
sampAll$TOTAL <- sampAll$HNUM+sampAll$GNUM
write.csv(sampAll,"./sample_counts/sample_count_table.csv",row.names=F,quote=F)

sampAll <- read.csv("./sample_counts/sample_count_table.csv")
#making the plot.
#1. do a regression between TOTAL(x) and GNUM(y)
fit <- lm(sampAll$GNUM~sampAll$TOTAL)

lims <- c(min(sampAll$TOTAL,sampAll$GNUM),max(sampAll$TOTAL))

#do the plot
tiff("./figures/genebank_vs_total.tif",
         res=300,pointsize=12,width=1500,height=1500,units="px",compression="lzw")
par(mar=c(5,5,1,1),cex=0.8)
plot(sampAll$TOTAL,sampAll$GNUM,pch=20,cex=1,xlim=lims,ylim=c(0,1500),
     xlab="Total number of samples",
     ylab="Number of genebank accessions")
abline(0,1,lwd=0.75,lty=2)
abline(h=500,lwd=0.75,lty=1,col="red")
abline(h=100,lwd=0.75,lty=2,col="red")
lines(sampAll$TOTAL,fit$fitted.values)
grid(lwd=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_sativus")]+1100,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_sativus")]+50,
     "L. sativus",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_cicera")]+1000,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_cicera")]+50,
     "L. cicera",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_aphaca")]+1100,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_aphaca")],
     "L. aphaca",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_linifolius")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_linifolius")]+50,
     "L. linifolius",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_pratensis")]-600,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_pratensis")]+50,
     "L. pratensis",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_silvestris")]+1100,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_silvestris")]-50,
     "L. silvestris",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_hierosolymitanus")]+2000,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_hierosolymitanus")],
     "L. hierosolymitanus",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_ochrus")]+1000,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_ochrus")],
     "L. ochrus",cex=0.75)
dev.off()


#sub-plot with zoomed stuff
#do the plot
tiff("./figures/genebank_vs_total_zoom.tif",
         res=300,pointsize=12,width=1500,height=1500,units="px",compression="lzw")
par(mar=c(5,5,1,1),cex=0.8)
plot(sampAll$TOTAL,sampAll$GNUM,pch=20,cex=1,xlim=c(0,2500),ylim=c(0,2500),
     xlab="Total number of samples",
     ylab="Number of genebank accessions")
abline(0,1,lwd=0.75,lty=2)
lines(sampAll$TOTAL,fit$fitted.values)
grid(lwd=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_sativus")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_sativus")]+50,
     "L. sativus",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_cicera")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_cicera")]+50,
     "L. cicera",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_hierosolymitanus")]-200,
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_hierosolymitanus")]+50,
     "L. hierosolymitanus",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_ochrus")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_ochrus")]+50,
     "L. ochrus",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_palustris")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_palustris")]-50,
     "L. palustris",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_nissolia")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_nissolia")]-50,
     "L. nissolia",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_niger")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_niger")]-50,
     "L. niger",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_hirsutus")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_hirsutus")]+50,
     "L. hirsutus",cex=0.75)
text(sampAll$TOTAL[which(sampAll$TAXON=="Lathyrus_clymenum")],
     sampAll$GNUM[which(sampAll$TAXON=="Lathyrus_clymenum")]+50,
     "L. clymenum",cex=0.75)
dev.off()



#####################
# sampAll$TOTAL_LOG <- log(sampAll$TOTAL)
# sampAll$GNUM_LOG <- log(sampAll$GNUM)
# sampAll$HNUM_LOG <- log(sampAll$HNUM)
# 
# plot(sampAll$TOTAL_LOG,sampAll$GNUM,pch=20,cex=1,#xlim=lims,ylim=lims,
#      xlab="Total number of samples",
#      ylab="Number of genebank accessions")
# 
# fit <- lm(sampAll$GNUM~sampAll$TOTAL)
# lines(sampAll$TOTAL_LOG,fit$fitted.values)
