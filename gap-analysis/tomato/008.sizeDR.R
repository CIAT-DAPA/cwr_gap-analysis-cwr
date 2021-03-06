require(rgdal)
require(raster)

source(paste(src.dir,"/000.zipRead.R",sep=""))
source(paste(src.dir,"/000.zipWrite.R",sep=""))
source(paste(src.dir,"/000.bufferPoints.R",sep=""))

#Calculate the size of the DR, of the convexhull in km2, of the native area, and of the herbarium samples
#based on the area of the cells

sizeDR <- function(bdir, spID) {
	
	idir <- paste(bdir, "/maxent_modeling", sep="")
	ddir <- paste(bdir, "/samples_calculations", sep="")
	
	#Creating the directories
	if (!file.exists(ddir)) {
		dir.create(ddir)
	}
	
	spOutFolder <- paste(ddir, "/", spID, sep="")
	if (!file.exists(spOutFolder)) {
		dir.create(spOutFolder)
	}
	
  #Read the thresholded raster (PA), multiply it by the area raster and sum up those cells that are != 0
	cat("Taxon", spID, "\n")
	spFolder <- paste(idir, "/models/", spID, sep="")
	projFolder <- paste(spFolder, "/projections", sep="")
	
	mskArea <- paste(idir, "/masks/cellArea.asc", sep="")
	mskArea <- raster(mskArea, values=T)
	msk <- paste(idir, "/masks/mask.asc", sep="")
	msk <- raster(msk)
	
	#Size of the DR
	cat("Reading raster files \n")
	grd <- paste(spID, "_worldclim2_5_EMN_PA.asc.gz", sep="")
	if (file.exists(paste(projFolder, "/", grd, sep=""))) {
		grd <- zipRead(projFolder, grd)
		
		cat("Size of the DR \n")
		grd <- grd * mskArea
		areaDR <- sum(grd[which(grd[] != 0)])
		rm(grd)
	} else {
		areaDR <- NA
	}
	
	#Size of the convex-hull
	cat("Reading occurrences \n")
	occ <- read.csv(paste(idir, "/occurrence_files/", spID, ".csv", sep=""))
	
  if (!file.exists(paste(spOutFolder, "/convex-hull.asc.gz",sep=""))) {
  	cat("Creating the convex hull \n")
  	ch <- occ[chull(cbind(occ$lon, occ$lat)),2:3]
  	ch <- rbind(ch, ch[1,])
  	
  	cat("Transforming to polygons \n")
  	pol <- SpatialPolygons(list(Polygons(list(Polygon(ch)), 1)))
  	grd <- rasterize(pol, msk)
  	
  	cat("Final fixes \n")
  	grd[which(!is.na(grd[]))] <- 1
  	grd[which(is.na(grd[]) & msk[] == 1)] <- 0
  	grd[which(is.na(msk[]))] <- NA
  	
  	cat("Writing convex hull \n")
  	chName <- zipWrite(grd, spOutFolder, "convex-hull.asc.gz")
  } else {
    cat("Loading the convex hull \n")
    grd <- zipRead(spOutFolder, "convex-hull.asc.gz")
  }
	cat("Size of the convex hull \n")
	grd <- grd * mskArea
	areaCH <- sum(grd[which(grd[] != 0)])
	rm(grd)
	
	#Size of the native area
	
	cat("Reading native area \n")
	naFolder <- paste(idir, "/native-areas/asciigrids/", spID, sep="")
	
	if (file.exists(paste(naFolder, "/narea.asc.gz", sep=""))) {
		grd <- zipRead(naFolder, "narea.asc.gz")
		cat("Size of the native area \n")
		grd <- grd * mskArea
		areaNA <- sum(grd[which(grd[] != 0)])
		rm(grd)
	} else {
		areaNA <- NA
	}
	
	#Load all occurrences
  allOcc <- read.csv(paste(bdir, "/occurrences/",crop,".csv", sep=""))
	allOcc <- allOcc[which(allOcc$Taxon == spID),]
	
	#Size of the herbarium samples CA50
	cat("Size of the h-samples buffer \n")
	hOcc <- allOcc[which(allOcc$H == 1),]
	if (nrow(hOcc) != 0) {
		hOcc <- as.data.frame(cbind(as.character(hOcc$Taxon), hOcc$lon, hOcc$lat))
		names(hOcc) <- c("taxon", "lon", "lat")
		
		write.csv(hOcc, paste(spOutFolder, "/hsamples.csv", sep=""), quote=F, row.names=F)
		rm(hOcc)
    
    if (!file.exists(paste(spOutFolder, "/hsamples-buffer.asc.gz",sep=""))) {
		  grd <- createBuffers(paste(spOutFolder, "/hsamples.csv", sep=""), spOutFolder, "hsamples-buffer.asc", 50000, paste(idir, "/masks/mask.asc", sep=""))
    } else {
      grd <- zipRead(spOutFolder,"hsamples-buffer.asc.gz")
    }
		grd <- grd * mskArea
		areaHB <- sum(grd[which(grd[] != 0)])
	} else {
		areaHB <- 0
	}
	
	#Size of the germplasm samples CA50
	cat("Size of the g-samples buffer \n")
	gOcc <- allOcc[which(allOcc$G == 1),]
	if (nrow(gOcc) != 0) {
		gOcc <- as.data.frame(cbind(as.character(gOcc$Taxon), gOcc$lon, gOcc$lat))
		names(gOcc) <- c("taxon", "lon", "lat")
		
		write.csv(gOcc, paste(spOutFolder, "/gsamples.csv", sep=""), quote=F, row.names=F)
		rm(gOcc)
    
    if (!file.exists(paste(spOutFolder,"/gsamples-buffer.asc.gz",sep=""))) {
		  grd <- createBuffers(paste(spOutFolder, "/gsamples.csv", sep=""), spOutFolder, "gsamples-buffer.asc", 50000, paste(idir, "/masks/mask.asc", sep=""))
    } else {
      grd <- zipRead(spOutFolder,"gsamples-buffer.asc.gz")
    }
		grd <- grd * mskArea
		areaGB <- sum(grd[which(grd[] != 0)])
	} else {
		areaGB <- 0
	}
	
	outDF <- data.frame(DRSize=areaDR, CHSize=areaCH, NASize=areaNA, HBSize=areaHB, GBSize=areaGB)
	
	write.csv(outDF, paste(spOutFolder, "/areas.csv", sep=""), quote=F, row.names=F)
	return(outDF)
}

summarizeDR <- function(idir) {
	
	ddir <- paste(idir, "/samples_calculations", sep="")
	
	odir <- paste(idir, "/maxent_modeling/summary-files", sep="")
	if (!file.exists(odir)) {
		dir.create(odir)
	}
	
	spList <- list.files(paste(idir, "/maxent_modeling/occurrence_files", sep=""))
	
	sppC <- 1
	for (spp in spList) {
		spp <- unlist(strsplit(spp, ".", fixed=T))[1]
		fdName <- spp #paste("sp-", spp, sep="")
		spFolder <- paste(idir, "/maxent_modeling/models/", fdName, sep="")
		spOutFolder <- paste(ddir, "/", spp, sep="")
		
		if (file.exists(spFolder)) {
			
			res <- sizeDR(idir, spp)
			
			metFile <- paste(spOutFolder, "/areas.csv", sep="")
			metrics <- read.csv(metFile)
			metrics <- cbind(taxon=spp, metrics)
			
			if (sppC == 1) {
				outSum <- metrics
			} else {
				outSum <- rbind(outSum, metrics)
			}
			sppC <- sppC + 1
		} else {
			cat("The taxon was never modeled \n")
		}
	}
	
	outFile <- paste(odir, "/areas.csv", sep="")
	write.csv(outSum, outFile, quote=F, row.names=F)
	return(outSum)
}
