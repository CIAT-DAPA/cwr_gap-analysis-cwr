#Phaseolus case study
#Julian Ramirez-Villegas
#CIAT
#March 2012
stop("Warning: do not run the whole thing")

#basic stuff
src.dir <- "D:/_tools/gap-analysis-cwr/trunk/gap-analysis/original-version-phaseolus"

#crop details
crop_dir <- "D:/CIAT_work/Gap_analysis/ICARDA-collab/phaseolus"
occ_dir <- paste(crop_dir,"/occurrences",sep="")
cli_dir <- "D:/CIAT_work/climate_change/wcl_2_5min/bio"
swd_dir <- paste(crop_dir,"/swd",sep="")
if (!file.exists(swd_dir)) {dir.create(swd_dir)}

#extract climate data
source(paste(src.dir,"/001.extractClimates.R",sep=""))
x <- extractClimates(input_dir=occ_dir,sample_file="phaseolus_all.csv",env_dir=cli_dir,
                     env_prefix="bio_",env_ext="",lonfield="Longitude",
                     latfield="Latitude",taxfield="Taxon",output_dir=swd_dir)




