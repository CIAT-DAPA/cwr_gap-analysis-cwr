#JRV April 2013
#CIAT
stop("pls dont run yet!")

library(ggplot2)
library(RColorBrewer)

#1. scattergram
#2. RD histogram (RD=(FPS-EPS)/10 * 100)
#3. multi-expert for single genepool
#4. multi-genepool (using RD, or using all data points). Maybe a single histogram and then each 
#   histogram in thin grey in background

wd <- "D:/_tools/gap-analysis-cwr/trunk/gap-analysis/eval"
setwd(wd)

source("eval-functions.R")

#select genepool
gpool <- "phaseolus"

#load model results
rMod <- read.table("./model/fps_all.tab",header=T,sep="\t")

#load expert evaluation
com_scores <- prepare_exp(ex_file=paste("./expert/res_",gpool,".tab",sep=""),ex_score="Comparable",rMod)
con_scores <- prepare_exp(ex_file=paste("./expert/res_",gpool,".tab",sep=""),ex_score="Contextual",rMod)

### compute the models for each expert and for consensus
com_fit <- fit_reg(com_scores) #comparable
con_fit <- fit_reg(con_scores) #contextual

### scattergram
com_plot <- plot_scatter(fits=com_fit,scores=com_scores,file_name=paste("./figures/scattergram/",gpool,"_comparable.png",sep=""))
con_plot <- plot_scatter(fits=con_fit,scores=con_scores,file_name=paste("./figures/scattergram/",gpool,"_contextual.png",sep=""))

### RD histogram plot
com_bplot <- plot_bars(fits=com_fit,scores=com_scores,file_name=paste("./figures/barplot/",gpool,"_comparable.png",sep=""))
con_bplot <- plot_bars(fits=con_fit,scores=con_scores,file_name=paste("./figures/barplot/",gpool,"_contextual.png",sep=""))

#save outputs
save(list=c("com_fit","com_scores","com_bplot","con_fit","con_scores","con_bplot"),file=paste("./output/",gpool,".RData",sep=""))


####################################################################
####################################################################
### generalised evaluation

#load results
all_res <- read.table("./general_table.tab",header=T,sep="\t")

#filter data for the two scores
com_res <- filter_data(in_data=all_res,ex_score="Comparative")
con_res <- filter_data(in_data=all_res,ex_score="Contextual")

#fitting the results
com_fit <- fit_gp(exp_res=com_res)
con_fit <- fit_gp(exp_res=con_res)

#plotting all stuff
com_plot <- plot_scatter_all(fits=com_fit,exp_res=com_res,file_name="./figures/scattergram/all_GPs_comparative.png")
con_plot <- plot_scatter_all(fits=con_fit,exp_res=con_res,file_name="./figures/scattergram/all_GPs_contextual.png")

#plot a pdf of the rho values
png("./figures/density_summary.png",width=2048,height=1800,res=300,pointsize=13)
par(mar=c(4.5,4.5,1,1))
plot(density(com_fit$RHO$RHO,na.rm=T),ylim=c(0,1),col="red",
     xlab=expression(paste(plain("Spearman coefficient ("),rho,plain(")"))),ylab="Density",main=NA,lwd=1.5)
lines(density(con_fit$RHO$RHO,na.rm=T),ylim=c(0,1),col="blue",lwd=1.5)
legend(x=-1.25,y=1,legend=c("Comparative","Contextual"),col=c("red","blue"),bg="white",
       lty=c(1,1),lwd=c(1.5,1.5))
grid()
dev.off()



