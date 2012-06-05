# Mapforgen threats analysis
# N. Castaneda, 2012

require(raster)
require(SDMTools)
require(maptools)

wd <- "C:/Users/ncp148/Dropbox_CGIAR/Dropbox/Mapforgen_data/dist2"
setwd(wd)

#rs <- raster("C:/Users/ncp148/Dropbox_CGIAR/Dropbox/Mapforgen_data/dist/acr_acul")

# Set variables
tdir <- "C:/Users/ncp148/Dropbox_CGIAR/Dropbox/Mapforgen_data/imm_threats_2-5min"
outFolder <- "C:/Users/ncp148/Dropbox_CGIAR/Dropbox/Mapforgen_data/cntry_threats"
mask <- raster("./acr_acul")
cntry_msk <- readShapePoly("C:/Users/ncp148/Dropbox_laguanegna/Dropbox/Mapforgen/cntries/_Latino_ISO_Code_WGS84.shp") #maptools
cntry_msk <- rasterize(cntry_msk, mask)

#ecorg_msk <- readShapePoly() #maptools
#ecorg_msk <- rasterize()

#-------------------------------
# Steps
#-------------------------------

# 1. Multiply
# 2. Zonal statistics
# 3. Extract

#-------------------------------
# Process
#-------------------------------

# List rasters
spList <- list.files(pattern = "*_*")
for (sp in spList){
  cat("Processing",sp,"\n")
  spr <- raster(sp)

  #Reclassify species raster
  spr[which(spr[] !=0)] <- 1

  #List threats
  tList <- c("access_pop","conv_ag", "fires", "grazing", "infrastr", 
             "oil_gas", "rec_conv")
  
  for (t in tList){
    # Preparing threat layer
    tr <- raster(paste(tdir,"/",t,sep=""))
    tr <- resample(tr, spr, method="bilinear")
    # Threat name
    t <- substr(t,1,3)
    # Country - threats: ISO3, THREAT, SP_CODE, MEAN
    cntry_threat <- spr * tr   
    # Saving maps
    ct <- writeRaster(cntry_threat, paste(outFolder, "/", sp, "_", t, ".asc", sep=""), overwrite=T)
    rm(ct)
    # Zonal stats per country
    cntry_threat <- zonal(cntry_threat,cntry_msk, stat="mean", digits=2, na.rm=TRUE) #raster
    cntry_threat <- cbind(t,sp,cntry_threat)
    # Zonal stats per ecoregion
    eco_threat <- zonal(cntry_threat,cntry_msk, stat="mean", digits=2, na.rm=TRUE) #raster
    eco_threat <- cbind(t,sp,cntry_threat)
    
    }

}

# Country - threats: ISO3, THREAT, SP_CODE, MEAN
#ZonalStat(mat,zones,FUN='all') 

#sp <- raster("C:/Users/ncp148/Dropbox_CGIAR/Dropbox/Mapforgen_data/richness maps 22_5_12/ObssppRich/continent/ASC/observed_spp_richLA.asc")



#cntry_threat <- ZonalStat(sp, cntry_msk, FUN='mean') #SDMTools
cntry_threat <- zonal(sp,cntry_msk, stat="mean", digits="2", na.rm=TRUE) #raster
cntry_threat <- cbind(threat,spp,cntry_threat)

# Ecoregions - threats: ECOREG, THREAT, SP_CODE, MEAN


# Continent - threats: THREAT, SP_CODE, MEAN