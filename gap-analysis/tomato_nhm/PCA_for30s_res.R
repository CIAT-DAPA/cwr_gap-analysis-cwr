#############################
# PCA -light version
# N. Castaneda
# Sept 7, 2012
#############################

require(raster)
require(stats)

rm(list=ls()); g=gc(); rm(g)

wd <- "/curie_data2/ncastaneda/geodata/bio_30s_sa"
#wd <- "C:/Users/ncp148/Documents/_geodata/bioclim/bio_10m_esri"
setwd(wd)

stk <- stack(paste("bio_",1:19,".asc",sep="")) #all variables
#stk <- stack(paste("bio_",1:5,sep="")) # ONLY TEST PURPOSES

#ext <- extent(c(-80,-65,-2,13)) # ONLY TEST PURPOSES
#stk <- crop(stk,ext) # ONLY TEST PURPOSES
#plot(stk)# ONLY TEST PURPOSES

rs <- stk[[1]]
xy <- xyFromCell(rs,which(!is.na(rs[])))
xy <- as.data.frame(xy) #cells coordinates

# Extract data
for (i in 1:19) {
  cat("Extract",i,"\n")
  nam <- paste("bio",i,sep="_")
  dat <- extract(stk[[i]],data.frame(X=xy$x,Y=xy$y))
  assign(nam,dat)
  }

bios <-cbind(xy,bio_1, bio_2, bio_3, bio_4, bio_5, bio_6, bio_7, bio_8, bio_9, bio_10,
             bio_11, bio_12, bio_13, bio_14, bio_15, bio_16, bio_17, bio_18, bio_19)

biovars <- bios[,3:21] # When all 19 biovars are included

# PCA analysis
x <- prcomp(biovars, scale=T)
xs <- summary(x)
y <- as.data.frame(predict(x,biovars))

dir.create("pca_result_raw")

# Plot Principal Components
#for (i in 1:19) {
for (i in 1:2) {
  cat("Assign",i,"\n")
  rs <- raster(stk)
  wcells <- cellFromXY(rs,data.frame(X=xy$x,Y=xy$y))
  rs[] <- NA
  rs[wcells] <- y[,paste("PC",i,sep="")]
  rs <- writeRaster(rs,paste("./pca_result_raw/pc_",i,".asc",sep=""),format='ascii')
}

write.csv(xs$importance,"./pca_result_raw/pca_importance.csv",quote=F,row.names=T)

#reclassify components
#dir.create("./../pca_result_reclass")
dir.create("./pca_result_reclass")

for (i in 1:2) {
  #for (i in 1:21) {
  cat("\nVariable",i,"\n")
  rs <- raster(paste("./pca_result_raw/pc_",i,".asc",sep=""))
  rs_res <- rs
  
  mx <- max(rs[],na.rm=T)
  mn <- min(rs[],na.rm=T)
  intval <- (mx-mn)/22
  brks <- seq(mn,mx,by=intval)
  
  for (cls in 1:20) {
    cat(cls," ")
    if (cls!=20) {
      rs_res[which(rs[]>=brks[cls] & rs[]<brks[cls+1])] <- cls
    } else {
      rs_res[which(rs[]>=brks[cls] & rs[]<=brks[cls+1])] <- cls
    }
  }
  cat("\n")
  rs_res <- writeRaster(rs_res,
                        paste("./pca_result_reclass/pc_r_",i,".asc",sep=""),
                        format='ascii')
  #plot(rs_res) DO NOT RUN THIS ON CURIE!
  rm(rs_res); g=gc(); rm(g)
  }


