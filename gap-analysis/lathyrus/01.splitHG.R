#ICARDA and CIAT Collaboration on gap analysis
#March 2012
stop("")

library(raster); library(maptools); data(wrld_simpl)

wd <- "D:/CIAT_work/Gap_analysis/ICARDA-collab/lathyrus"
setwd(wd)

occ <- read.csv("./occurrences/lathyrus.csv")
h <- occ[which(occ$H==1),]
g <- occ[which(occ$G==1),]

write.csv(h,"./occurrences/lathyrus_h.csv",quote=F,row.names=F)
write.csv(g,"./occurrences/lathyrus_g.csv",quote=F,row.names=F)

###sample counts map
h_ras <- raster("./sample_counts/count_h.asc")
g_ras <- raster("./sample_counts/count_g.asc")

h_ras[which(h_ras[]==0)] <- NA; g_ras[which(g_ras[]==0)] <- NA

brks <- unique(quantile(c(h_ras[],g_ras[]),na.rm=T,probs=c(seq(0,1,by=0.05))))
cols <- colorRampPalette(c("dark green","yellow","orange","red"))(length(brks)-1)
brks.lab <- round(brks,0)

if (!file.exists("./figures")) {dir.create("./figures")}

z <- extent(h_ras)
aspect <- (z@ymax-z@ymin)*1.35/(z@xmax-z@xmin)

tiff("./figures/h_samples_count.tif",
         res=300,pointsize=7,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8)
plot(h_ras,col=cols,zlim=c(min(brks),max(brks)),
     breaks=brks,lab.breaks=brks.lab,useRaster=F,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5)
grid()
dev.off()

#germplasm map
tiff("./figures/g_samples_count.tif",
         res=300,pointsize=7,width=1500,height=1500*aspect,units="px",compression="lzw")
par(mar=c(2.5,2.5,1,1),cex=0.8)
plot(g_ras,col=cols,zlim=c(min(brks),max(brks)),useRaster=F,
     breaks=brks,lab.breaks=brks.lab,
     horizontal=T,
     legend.width=1,
     legend.shrink=0.99)
plot(wrld_simpl,add=T,lwd=0.5)
grid()
dev.off()

