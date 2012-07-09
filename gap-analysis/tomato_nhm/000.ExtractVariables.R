##################################################
## Extract bioclim variables and convert to asc ##
## Author: N. Castaneda                         ##
## Date: 2012-07-09                             ##
##################################################

require(raster)

# Setting variables
crop <- "tomato"
crop_dir <- paste("G:/ncastaneda/gap-analysis-",crop,"/gap_",crop,sep="")
setwd(crop_dir)

clm_g <- "G:/ncastaneda/clim/bio_30s_esri"
ext <- (-110,-29,-56,14)

# Creating folders
if (!file.exists("./maxent_modeling")) {dir.create("./maxent_modeling")}
if (!file.exists("./maxent_modeling/climate_data")) {dir.create("./maxent_modeling/climate_data")}

clm_fldr <- "./maxent_modeling/climate_data"

#if(!file.exists(paste(clm_fldr,"/esri_grid", sep=""))) {dir.create(paste(clm_fldr,"/esri_grid",sep=""))}
if(!file.exists(paste(clm_fldr,"/esri_ascii", sep=""))) {dir.create(paste(clm_fldr,"/esri_ascii",sep=""))}
  
#esri <- paste(clm_fldr,"/esri_grid", sep="")
asci <- paste(clm_fldr,"/esri_ascii", sep="")

# Process
for (i in 1:20) {  
  cat("Processing layer bio_",i,"\n")
  rs <- raster(paste(clm_g,"/bio_",i,sep=""))
  rs <- extract(rs, ext)
  writeRaster(rs, paste(asci,"/bio_",i,".asc")) 
}
