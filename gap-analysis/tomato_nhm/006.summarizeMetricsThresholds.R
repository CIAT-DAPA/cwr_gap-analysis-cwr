#Extract the AUC evaluation statistics to select species with relatively high AUC (i.e. >.65 or .7)
#Test and train AUCs should be extracted from cross validated runs

cat(" \n")
cat("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX \n")
cat("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX \n")
cat("XXXXXXXX SUMMARIZE METRICS SCRIPT XXXXXXXXXX \n")
cat("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX \n")
cat("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX \n")

cat(" \n")
cat(" \n")

summarizeMetrics <- function(idir="E:/CIAT/maxent_modelling") {

	#idir <- "/mnt/GeoData/Gap-analysis/cwr-gap-analysis/gap-phaeolus/modeling_data"
	
	#Setting the output directory
	odir <- paste(idir, "/summary-files", sep="")
	
	if (!file.exists(odir)) {
		dir.create(odir)
	}
	
	spList <- list.files(paste(idir, "/occurrence_files", sep=""))
	nspp <- nrow(spList)
	
	sppC <- 1
	sppCC <- 1
	for (spp in spList) {
		spp <- unlist(strsplit(spp, ".", fixed=T))[1]
		fdName <- spp #paste("sp-", spp, sep="")
		spFolder <- paste(idir, "/models/", fdName, sep="")
		
		#Performing only for existing folders
		if (file.exists(spFolder)) {
			
			cat("Processing species", spp, paste("...",round(sppCC/length(spList)*100,2),"%",sep=""), "\n")
			
			#Loading metrics files and adding one more field (SPID)
			metricsFile <- paste(spFolder, "/metrics/metrics.csv", sep="")
      
      #these two need to be created manually only once
      dumMetFile <- paste(idir,"/models/metricsDummy.csv",sep="")
      dumThreshFile <- paste(idir,"/models/threshDummy.csv",sep="")
      if (file.exists(metricsFile)) {
			  metrics <- read.csv(metricsFile)
        threshFile <- paste(spFolder, "/metrics/thresholds.csv", sep="")
        thresholds <- read.csv(threshFile)
      } else {
        metrics <- read.csv(dumMetFile)
        thresholds <- read.csv(dumThreshFile)
      }
			
			#Adding one more field (SPID)
      metrics <- cbind(SPID=spp, metrics)
			thresholds <- cbind(SPID=spp, thresholds)
		  
			#Comprising everything onto a matrix
			if (sppC == 1) {
				finRes <- metrics
				finThr <- thresholds
				rm(thresholds)
				rm(metrics)
			} else {
				finRes <- rbind(finRes, metrics)
				finThr <- rbind(finThr, thresholds)
				rm(thresholds)
				rm(metrics)
			}
			
			sppC <- sppC + 1
		} else {
			cat("The species", spp, "was not modeled", paste("...",round(sppCC/length(spList)*100,2),"%",sep=""), "\n")
		}
		sppCC <- sppCC + 1
	}

	#Now writing the outputs
	cat("\n")
	cat("Writing outputs... \n")
	oFile <- paste(odir, "/accuracy.csv", sep="")
	write.csv(finRes, oFile, quote=F, row.names=F)
	
	oFile <- paste(odir, "/thresholds.csv", sep="")
	write.csv(finThr, oFile, quote=F, row.names=F)
	
	#Return the metrics data-frame
	return(finRes)
}
