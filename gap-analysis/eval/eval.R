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


