#Gap analysis workshop
#March 2012
stop("")

# Define workspace
#wd <- "C:/Users/ncp148/Documents/CPP_CWR/_collaboration/_fontagro/gap_tomato"
wd <- "G:/ncastaneda/gap-analysis-tomato/gap_tomato"
setwd(wd)

#read occurrences
occ <- read.csv(paste("./occurrences/",crop,".csv",sep=""))
taxField <- "Taxon"
hField <- "H"
gField <- "G"

#taxon unique values
taxNames <- unique(occ[,taxField])

for (tax in taxNames) {
  cat("Counting",paste(tax),"\n")
  
  #subselect the data for this taxon
  taxData <- occ[which(occ[,taxField]==tax),]
  taxDataQ <- data.frame(paste(taxData[,taxField]),taxData[,"lon"],taxData[,"lat"],taxData[,hField],taxData[,gField])
  names(taxDataQ) <- c(taxField,"lon","lat",hField,gField)
  
  allData <- taxDataQ; allData[,hField] <- NULL; allData[,gField] <- NULL
  hData <- taxDataQ[which(taxDataQ[,hField]==1),]
  hData[,hField] <- NULL; hData[,gField] <- NULL
  gData <- taxDataQ[which(taxDataQ[,gField]==1),]
  gData[,hField] <- NULL; gData[,gField] <- NULL
  
  #count herbarium recs and germplasm samples (totals regardless of populations)
  hc <- sum(taxData[,hField])
  gc <- sum(taxData[,gField])
  total <- nrow(taxData)
  
  #count h and g samples (totals but only considering populations -unique coordinates)
  hc_u <- nrow(unique(hData))
  gc_u <- nrow(unique(gData))
  total_u <- nrow(unique(allData))
  
  row_out <- data.frame(TAXON=paste(tax),HNUM=hc,GNUM=gc,HNUM_RP=hc_u,GNUM_RP=gc_u,TOTAL_RP=total_u)
  
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
         res=300,pointsize=8, width=1000,height=1000,units="px",compression="lzw")
par(mar=c(5,5,1,1),cex=0.8)
plot(sampAll$TOTAL,sampAll$GNUM,pch=20, col="red",cex=1,xlim=lims,ylim=lims,
     xlab="Total samples",
     ylab="Germplasm accessions")
abline(0,1,lwd=0.75,lty=2)
#abline(h=500,lwd=0.75,lty=1,col="red")
#abline(h=100,lwd=0.75,lty=2,col="red")
lines(sampAll$TOTAL,fit$fitted.values)
grid(lwd=0.75)

# NOTE: Personalize this according to the crop you're working with!
text(sampAll$TOTAL[which(sampAll$TAXON=="Musa_acuminata_banksii")]-5,
     sampAll$GNUM[which(sampAll$TAXON=="Musa_acuminata_banksii")]-1,
     "M. acuminata banksii",cex=0.55, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Musa_textilis")],
     sampAll$GNUM[which(sampAll$TAXON=="Musa_textilis")]+2,
     "M. textilis",cex=0.55, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Musa_schizocarpa")],
     sampAll$GNUM[which(sampAll$TAXON=="Musa_schizocarpa")]+2,
     "M. schizocarpa",cex=0.55, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Musa_acuminata_malaccensis")],
     sampAll$GNUM[which(sampAll$TAXON=="Musa_acuminata_malaccensis")]+2,
	"M. acuminata malaccensis",cex=0.55, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Musa_acuminata_siamea")],
     sampAll$GNUM[which(sampAll$TAXON=="Musa_acuminata_siamea")]+2,
	"M. acuminata siamea",cex=0.55, font=3)

dev.off()


