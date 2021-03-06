#Gap analysis workshop
#March 2012
#stop("")

library(raster); library(maptools); data(wrld_simpl)

###sample counts map
r <- raster()
res(r) <- 1

g_ras <- read.csv(paste("./occurrences/",crop,"_g.csv",sep=""))
g_ras <- g_ras[,c("lon","lat")]
g_ras <- rasterize(g_ras,r,fun="count")
  
h_ras <- read.csv(paste("./occurrences/",crop,"_h.csv",sep=""))
h_ras <- h_ras[,c("lon","lat")]
h_ras <- rasterize(h_ras,r,fun="count")

#h_ras <- raster("./sample_counts/_count_h.asc")
#g_ras <- raster("./sample_counts/_count_g.asc")

h_ras[which(h_ras[]==0)] <- NA; g_ras[which(g_ras[]==0)] <- NA

brks <- unique(quantile(c(h_ras[],g_ras[]),na.rm=T,probs=c(seq(0,1,by=0.05))))
cols <- colorRampPalette(c("dark green","yellow","orange","red"))(length(brks)-1)
brks.lab <- round(brks,0)

if (!file.exists("./figures")) {dir.create("./figures")}

#h_ras <- trim(h_ras) # Test!
#g_ras <- trim(g_ras)
z <- extent(h_ras)
aspect <- (z@ymax-z@ymin)*1.4/(z@xmax-z@xmin)

#herbarium map
tiff("./figures/h_samples_count.tif",
         res=300,pointsize=5,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8,lwd=0.8)
plot(h_ras,col=cols,zlim=c(min(brks),max(brks)), main = "Herbarium samples",
     breaks=brks,lab.breaks=brks.lab,useRaster=F,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5, border="azure4")
grid()
dev.off()

#germplasm map
tiff("./figures/g_samples_count.tif",
         res=300,pointsize=5,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8, lwd=0.8)
plot(g_ras,col=cols,zlim=c(min(brks),max(brks)),useRaster=F, main="Genebank accessions",
     breaks=brks,lab.breaks=brks.lab,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5, border="azure4")
grid()
dev.off()

