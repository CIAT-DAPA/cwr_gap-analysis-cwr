#Phaseolus case study
#Julian Ramirez-Villegas
#CIAT
#March 2012
stop("Warning: do not run the whole thing")

#basic stuff
src.dir <- "D:/_tools/gap-analysis-cwr/trunk/gap-analysis/lathyrus"

#crop details
crop_dir <- "E:/CIAT"; setwd(crop_dir)

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
oDir <- "./maxent_modelling/occurrence_files"
x <- createOccFiles(occ="./swd/occurrences_swd_ok.csv",taxfield="Taxon",outDir=oDir)


#making the pseudo-absences
source(paste(src.dir,"/002.selectBackgroundArea.R",sep=""))
fList <- list.files("./maxent_modelling/occurrence_files",pattern=".csv")

for (f in fList) {
  paste("Processing",paste(f),"\n")
  iFile <- paste("./maxent_modelling/occurrence_files/",f,sep="")
  oFile <- paste("./maxent_modelling/background/",f,sep="")
  x <- selectBack(occFile=iFile, outBackName=oFile, 
                  msk="D:/CIAT_work/GBIF_project/backgroundFiles/backselection.asc", 
                  backFilesDir="D:/CIAT_work/GBIF_project/backgroundFiles")
}


#perform the maxent modelling in parallel
source(paste(src.dir,"/005.modelingApproach.R",sep=""))
GapProcess(inputDir=paste(crop_dir,"/maxent_modelling",sep=""), OSys="NT", ncpu=3)


#

