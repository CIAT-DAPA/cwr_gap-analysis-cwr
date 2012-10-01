##################################################
## Extract bioclim variables and save as asc    ##
## Author: N. Castaneda                         ##
## Date: 2012-07-09                             ##
##################################################

require(raster)

maskVariables <- function(crop_dir, env_dir){
  
  out_dir <- paste(crop_dir,"/biomod_modeling/current-clim",sep="")
  if (!file.exists(out_dir)) {dir.create(out_dir)}
  
  msk <- raster(paste(crop_dir,"/masks/mask.asc",sep=""))
  e <- extent(msk)
  
  for(i in 1:19){
    cat("Reading environmental layer", i, "\n")
    rs <- raster(paste(env_dir, "/bio_", i, sep=""))
    rs <- crop(rs,e)
    writeRaster(rs, paste(out_dir,"/bio_",i,".asc",sep=""), overwrite=T)    
  }    
}
