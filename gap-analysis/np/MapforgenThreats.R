# Mapforgen threats analysis
# N. Castaneda, 2012

require(raster)
require(SDMTools)
require(maptools)

wd <- "G:/ncastaneda/mapforgen/inputs"
setwd(wd)

#-------------------------------
# Set variables
#-------------------------------
tdir <- paste(wd,"/imm_threats_2-5min",sep="")
outFolder <- "G:/ncastaneda/mapforgen/outputs"

mask <- raster(paste(tdir,"/access_pop", sep=""))
rich <- raster(paste(wd,"/richness/cont_riq_mod.asc", sep=""))
cntry_msk <- readShapePoly(paste(wd,"/_sam_ISO_Code_WGS84.shp",sep="")) #maptools
cntry_msk <- rasterize(cntry_msk, mask)
cntry_cde <- read.table(paste(wd,"/countries.txt",sep=""), header=TRUE, sep=",")
myvars <- names(cntry_cde) %in% c("FID_", "ISO3")
cntry_cde <- cntry_cde[myvars]

ecorg_msk <- readShapePoly(paste(wd,"/_eco_zone_sam.shp",sep="")) #maptools
ecorg_msk <- rasterize(ecorg_msk, mask)
ecorg_cde <- read.table(paste(wd,"/ecoregions.txt", sep=""), header=TRUE, sep=",")

#-------------------------------
# Create folders
#-------------------------------
dir.create(paste(outFolder, "/GRD", sep=""))
dir.create(paste(outFolder, "/GRD/continent", sep=""))
dir.create(paste(outFolder, "/GRD/countries", sep=""))
dir.create(paste(outFolder, "/GRD/ecoregions", sep=""))
dir.create(paste(outFolder, "/GRD/species", sep=""))
dir.create(paste(outFolder, "/CSV", sep=""))

#-------------------------------
# Process
#-------------------------------

# List rasters
spList <- list.files(paste(wd,"/dist",sep=""), pattern=".asc")
for (sp in spList){
  cat("Processing",sp,"\n")
  spr <- raster(paste(wd, "/dist/", sp, sep=""))
  sp <- substr(sp,1,7)

  #Reclassify species raster
  spr[which(spr[] !=0)] <- 1
  #spr[which(spr[] == 0)] <- 1

  #List threats
  tList <- c("access_pop","conv_ag", "fires", "grazing_new", "infrastr", 
             "oil_gas", "rec_conv", "aggregate")
  
  for (t in tList){
    tr <- raster(paste(tdir,"/",t,sep=""))
    # Preparing layers for analysis
    y <- intersectExtent(spr, tr)
    spr <- crop(spr, y)
    tr <- crop(tr, y)
    spr <- resample(spr,tr,method="ngb")
  
    # Threat name
    t <- substr(t,1,3)
    # Threat analysis
    spp_threat <- spr * tr
    #spp_threat <- mask(tr,spr)
    # Saving maps
    ct <- writeRaster(spp_threat, paste(outFolder, "/GRD/species/", sp, "_", t, ".asc", sep=""), overwrite=T)
    rm(ct)
    # Stats per continent (THREAT, MEAN, SP_CODE)
    continent <- cellStats(spp_threat, stat="mean", na.rm=TRUE)
    continent <- cbind(t, continent, sp)
    colnames(continent) <- c("threat", "mean", "species")
    write.table(continent, paste(outFolder, "/CSV/regional_threats.csv", sep=""), append=T, sep=",", qmethod="double")
    # Stats per country (ISO3, THREAT, SP_CODE, MEAN)
    cntry_msk2 <- crop(cntry_msk, y)
    cntry_threat <- zonal(spp_threat,cntry_msk2, stat="mean", digits=2, na.rm=TRUE) #raster
    cntry_threat <- merge(cntry_threat, cntry_cde, by.x="zone", by.y="FID_")
    colnames(cntry_threat) <- c("zone", "mean", "ISO3")
    cntry_threat <- cbind(cntry_threat, t,sp)
    cntry_threat <- cntry_threat[,c("ISO3", "t", "sp", "mean")]
    cntry_threat <- cntry_threat[which(!is.na(cntry_threat$mean)),]
    cntry_threat <- cntry_threat[which(cntry_threat$t != "agg"),]
    write.table(cntry_threat, paste(outFolder, "/CSV/cntry_threats.csv", sep=""), append=T, sep=",", qmethod="double")
    # Stats per ecoregion (ECOREG, THREAT, SP_CODE, MEAN)
    ecorg_msk2 <- crop(ecorg_msk, y)
    eco_threat <- zonal(spp_threat,ecorg_msk2, stat="mean", digits=2, na.rm=TRUE) #raster
    eco_threat <- merge(eco_threat, ecorg_cde, by.x="zone", by.y="FID_")
    colnames(eco_threat) <- c("zone", "mean", "GEZ_TERM")
    eco_threat <- cbind(eco_threat, t,sp)
    eco_threat <- eco_threat[,c("GEZ_TERM", "t", "sp", "mean")]
    eco_threat <- eco_threat[which(!is.na(eco_threat$mean)),]
    eco_threat <- eco_threat[which(eco_threat$t != "agg"),]
    write.table(eco_threat, paste(outFolder, "/CSV/ecoreg_threats.csv", sep=""), append=T, sep=",", qmethod="double")

    }
}

    # Preparing maps
    rich[which(rich[] !=0)] <- 1
    rich <- resample(rich,tr,method="ngb")
    rich <- rich * tr
    writeRaster(rich, paste(outFolder, "/GRD/continent/", t, ".asc", sep=""), overwrite=T)
    # List countries shapefiles
    xmlList <- list.files(paste(wd,"/cntries",sep=""), pattern = "xml")
    shpList <- list.files(paste(wd,"/cntries",sep=""), pattern = "shp")
    shpList <- shpList[!shpList %in% xmlList]
      for (cn_name in shpList){
        shp_cn <- readShapePoly(paste(wd, "/cntries/", cn_name, sep=""))
        cntry <- mask(rich, shp_cn)
        cn_name <- substr(cn_name,1,3)
        writeRaster(cntry, paste(outFolder, "/GRD/countries/", cn_name, "_", t, ".asc", sep=""), overwrite=T)    
      }    
    # List ecoregions shapefiles
    xmlList <- list.files(paste(wd,"/ecorgs",sep=""), pattern = "xml")
    shpList <- list.files(paste(wd,"/ecorgs",sep=""), pattern = "shp")
    shpList <- shpList[!shpList %in% xmlList]
      for (ec_name in shpList){
        shp_ec <- readShapePoly(paste(wd, "/ecorgs/", ec_name,sep=""))
        ecoreg <- mask(rich, shp_ec)
        ec_name <- strsplit(ec_name, "_")
        ec_name <- unlist(ec_name)
        ec_name <- substring(ec_name, 1, 4)
        ec_name <- paste(ec_name[1], ec_name[2], ec_name[3], sep="_")        
        writeRaster(ecoreg, paste(outFolder, "/GRD/ecoregions/", ec_name, "_", t, ".asc", sep=""), overwrite=T)    
      }

 