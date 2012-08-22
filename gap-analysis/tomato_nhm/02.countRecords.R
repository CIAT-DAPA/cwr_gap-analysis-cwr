#Gap analysis workshop
#March 2012
#stop("")


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

if (!file.exists("./sample_counts")) {dir.create("./sample_counts")}
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
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_pimpinellifolium")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_pimpinellifolium")]-5,
     "pim",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_peruvianum")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_peruvianum")]-5,
     "per",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_chilense")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_chilense")]-5,
     "chi",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_juglandifolium")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_juglandifolium")]-5,
     "jug",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_sitiens")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_sitiens")]-5,
     "sit",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_lycopersicoides")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_lycopersicoides")]-5,
     "lyc",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_cheesmaniae")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_cheesmaniae")]-5,
     "che",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_neorickii")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_neorickii")]-5,
     "neo",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_pennellii")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_pennellii")]-5,
     "pen",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_ochranthum")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_ochranthum")]-5,
     "och",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_corneliomulleri")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_corneliomulleri")]-5,
     "cor",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_galapagense")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_galapagense")]-5,
     "gal",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_arcanum")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_arcanum")]-5,
     "arc",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_chmielewskii")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_chmielewskii")]-5,
     "chm",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_huaylasense")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_huaylasense")]-5,
     "hua",cex=0.5, font=3)
text(sampAll$TOTAL[which(sampAll$TAXON=="Solanum_habrochaites")],
     sampAll$GNUM[which(sampAll$TAXON=="Solanum_habrochaites")]-5,
     "hab",cex=0.5, font=3)
dev.off()