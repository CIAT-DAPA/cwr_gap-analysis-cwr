#Phaseolus case study
#Julian Ramirez-Villegas
#CIAT
#March 2012
stop("Warning: do not run the whole thing")

#basic stuff
src.dir <- "D:/_tools/gap-analysis-cwr/trunk/gap-analysis/lathyrus"

#crop details
crop_dir <- "D:/CIAT_work/Gap_analysis/ICARDA-collab/lathyrus"; setwd(crop_dir)

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


