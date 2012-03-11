#ICARDA collaboration
rm(list=ls()); g=gc(); rm(g)

wd <- "C:/Users/ncp148/Documents/CPP_CWR/_collaboration/_icarda/bio_10m_icarda"
setwd(wd)

stk <- stack(paste("bio_",1:19,sep=""))
rs <- stk[[1]]

xy <- xyFromCell(rs,which(!is.na(rs[])))
xy <- as.data.frame(xy)

#extract the data
for (i in 1:19) {
  cat("Extract",i,"\n")
  xy$NEW <- extract(stk[[i]],data.frame(X=xy$x,Y=xy$y))
  names(xy)[i+2] <- paste("bio_",i,sep="")
}

biovars <- xy[,3:21]
write.csv(biovars,"./../biovars_pca.csv",quote=F,row.names=F)

#standardise
biovars_std <- scale(biovars)
write.csv(biovars_std,"./../biovars_pca_std.csv",quote=F,row.names=F)

#do the pca
x <- prcomp(biovars,scale=T)
xs <- summary(x)
y <- as.data.frame(predict(x,biovars))

dir.create("./../pca_result_raw")

for (i in 1:19) {
  cat("Assign",i,"\n")
  rs <- raster(stk)
  wcells <- cellFromXY(rs,data.frame(X=xy$x,Y=xy$y))
  rs[] <- NA
  rs[wcells] <- y[,paste("PC",i,sep="")]
  rs <- writeRaster(rs,paste("./../pca_result_raw/pc_",i,".asc",sep=""),format='ascii')
}

rs <- raster("./../pca_result_raw/pc_2.asc")
write.csv(xs$importance,"./../pca_result_raw/pca_importance.csv",quote=F,row.names=T)

#reclassify components
dir.create("./../pca_result_reclass")

for (i in 1:19) {
  cat("\nVariable",i,"\n")
  rs <- raster(paste("./../pca_result_raw/pc_",i,".asc",sep=""))
  rs_res <- rs
  
  mx <- max(rs[],na.rm=T)
  mn <- min(rs[],na.rm=T)
  intval <- (mx-mn)/20
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
                        paste("./../pca_result_reclass/pc_r_",i,".asc",sep=""),
                        format='ascii')
  plot(rs_res)
  rm(rs_res); g=gc(); rm(g)
}


