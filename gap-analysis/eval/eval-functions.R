#JRV April 2013
#CIAT


#scattergram
plot_scatter_all <- function(fits,exp_res,file_name="./figures/scattergram/all_GPs_comparative.png") {
  exp_lm <- fits$LM
  exp_rho <- fits$RHO
  gp_list <- names(exp_lm); gp_list <- gp_list[which(gp_list != "ALL")]
  
  #start the device
  png(file_name,width=2048,height=1500,res=300,pointsize=13)
  
  #0. base graph
  par(mar=c(4,4,1,1))
  plot(0:10,0:10,xlim=c(0,10),ylim=c(0,10),pch=20,col="white",
       xlab="Model",ylab="Expert",font.axis=1,font.lab=2)
  grid()
  
  #1. shading of each expert and of the mean
  for (gp in gp_list) {
    #gp <- gp_list[1]
    tpol <- c(exp_lm[[paste(gp)]]$lwr,rev(exp_lm[[paste(gp)]]$upr))
    polygon(x=tpol,y=c(0:15,rev(0:15)),col="#00000020",border=NA)
  }
  
  #2. any available experts experts (individual lines in gray)
  for (gp in gp_list) {
    lines(exp_lm[[paste(gp)]]$fit,exp_lm[[paste(gp)]]$eps,col="grey 40",lwd=1.5)
  }
  
  #3. Shading of mean
  tpol <- c(exp_lm$ALL$lwr,rev(exp_lm$ALL$upr))
  polygon(x=tpol,y=c(0:15,rev(0:15)),col="#0000FF60",border=NA)
  
  #4. central line that is lm of average
  lines(exp_lm$ALL$fit,exp_lm$ALL$eps,col="blue",lwd=3.5)
  
  #5. points in black for mean of expers
  points(exp_res$FPS,exp_res$Expert,pch=21,cex=0.8)
  
  # #6. points in grey being individual experts
  # for (gp in gp_list) {
  #   this_gp <- exp_res[which(exp_res$Genepool == gp),]
  #   points(this_gp$FPS,this_gp$Expert,col="grey 30",pch=21)
  # }
  
  #put some text with the rho values
  rho_m <- round(exp_rho$RHO[nrow(exp_rho)],2)
  pval <- exp_rho$PVAL[nrow(exp_rho)]
  if (pval > .1) {
    signif <- "NS"
  } else if (pval <= .1 & pval > 0.05) {
    signif <- "*"
  } else if (pval <= .05 & pval > 0.01) {
    signif <- "**"
  } else if (pval <= .01) {
    signif <- "***"
  }
  wtext <- substitute(paste(rho,plain(" = "),rhm,plain(" "),sgn),list(rhm=rho_m,sgn=signif))
  text(0,9.5,cex=1,pos=4,wtext)
  
  dev.off()
  return(file_name)
}


### fitting all genepools for a given indicator
fit_gp <- function(exp_res) {
  #list of genepools
  gp_list <- unique(exp_res$Genepool)
  
  #output objects
  exp_lm <- list()
  exp_rho <- data.frame()
  for (gp in gp_list) {
    #gp <- gp_list[1]
    fitdata <- exp_res[which(exp_res$Genepool == gp),]
    fitdata <- data.frame(fps=fitdata$FPS,eps=fitdata$Expert)
    fitdata <- fitdata[which(!is.na(fitdata$fps)),]
    fitdata <- fitdata[which(!is.na(fitdata$eps)),]
    
    if (nrow(fitdata)>2) {
      mod <- lm(fps ~ eps,data=fitdata)
      fps_p <- predict(mod,newdata=data.frame(eps=0:15),interval="predict",level=0.2)
      fps_p <- as.data.frame(cbind(eps=0:15,fps_p))
      exp_lm[[paste(gp)]] <- fps_p
      rho <- cor.test(fitdata$fps,fitdata$eps,method="spearman")
      exp_rho <- rbind(exp_rho,data.frame(Genepool=gp,RHO=rho$estimate,PVAL=rho$p.value))
    } else {
      fps_p <- as.data.frame(cbind(eps=0:15,fit=NA,lwr=NA,upr=NA))
      exp_lm[[paste(gp)]] <- fps_p
      exp_rho <- rbind(exp_rho,data.frame(Genepool=gp,RHO=NA,PVAL=NA))
    }
  }
  
  #fit of average of both experts
  fitdata <- data.frame(fps=exp_res$FPS,eps=exp_res$Expert)
  fitdata <- fitdata[which(!is.na(fitdata$fps)),]
  fitdata <- fitdata[which(!is.na(fitdata$eps)),]
  
  mod <- lm(fps ~ eps,data=fitdata)
  fps_p <- predict(mod,newdata=data.frame(eps=0:15),interval="predict",level=0.2)
  fps_p <- as.data.frame(cbind(eps=0:15,fps_p))
  exp_lm[["ALL"]] <- fps_p
  rho <- cor.test(fitdata$fps,fitdata$eps,method="spearman")
  exp_rho <- rbind(exp_rho,data.frame(Genepool="ALL",RHO=rho$estimate,PVAL=rho$p.value))
  row.names(exp_rho) <- 1:nrow(exp_rho)
  
  out_obj <- list(LM=exp_lm,RHO=exp_rho)
  return(out_obj)
}

#filter data for a given score
filter_data <- function(in_data,ex_score="Comparative") {
  #ex_score <- "Comparative" #contextual
  ex_res <- in_data[,c("Genepool","Taxa","FPS",names(in_data)[grep(ex_score,names(in_data))])]
  ex_res$Expert <- apply(ex_res[,names(in_data)[grep(ex_score,names(in_data))]],1,FUN=function(x) {y <- x[which(!is.na(x))]; if (length(y)==0) {y<-NA} else {y <- mean(x,na.rm=T)}; return(y)})
  ex_res <- ex_res[which(!is.na(ex_res$Expert)),]
  return(ex_res)
}


###
### RD plot
#produce a bar plot of relative frequency in RD with error bars
#barplot of average and then error bars (use ggplot2)
plot_bars <- function(fits,scores,file_name="./figures/barplot/phaseolus_comparable.png") {
  require(ggplot2); require(RColorBrewer)
  
  exp_lm <- fits$LM
  exp_rho <- fits$RHO
  nexp <- length(grep("E\\.",names(scores))) #number of experts
  #brks <- c(seq(-100,-20,by=10),-0.0001,0.0001,seq(20,100,by=10)) #histogram breaks
  brks <- c(seq(-100,-10,by=10),-0.0001,0.0001,seq(10,100,by=10)) #histogram breaks
  if (nexp > 1) {
    for (i in 1:nexp) {
      frq <- hist(scores[,paste("RD.",i,sep="")],breaks=brks,plot=F)
      frq <- data.frame(XVAL=frq$mids,FREQ=(frq$counts)/sum(frq$counts))
      names(frq)[2] <- paste("E.",i,sep="")
      if (i == 1) {
        frq_out <- frq
      } else {
        frq_out <- merge(frq_out,frq,by="XVAL",sort=F)
      }
    }
    frq_out$MAX <- apply(frq_out[,paste("E.",1:nexp,sep="")],1,FUN=function(x) {max(x,na.rm=T)})
    frq_out$MIN <- apply(frq_out[,paste("E.",1:nexp,sep="")],1,FUN=function(x) {min(x,na.rm=T)})
    frq_out$MEAN <- apply(frq_out[,paste("E.",1:nexp,sep="")],1,FUN=function(x) {mean(x,na.rm=T)})
    
    frq <- hist(scores$RD,breaks=brks,plot=F)
    frq <- data.frame(XVAL=frq$mids,FREQ=(frq$counts)/sum(frq$counts))
    names(frq)[2] <- "ENS"
    frq_out <- merge(frq_out,frq,by="XVAL",sort=F)
  } else {
    frq <- hist(scores$RD,breaks=brks,plot=F)
    frq <- data.frame(XVAL=frq$mids,FREQ=(frq$counts)/sum(frq$counts))
    names(frq)[2] <- "MEAN"
    frq_out <- frq
    frq_out$MAX <- frq_out$MEAN
    frq_out$MIN <- frq_out$MEAN
    frq_out$ENS <- frq_out$MEAN
  }
  
  ### now the bar chart with ggplot2
  p <- ggplot(frq_out, aes(x = XVAL, y = MEAN)) + 
    geom_bar(width=7.5,stat="identity",size=1, fill="grey 50", colour="black") +
    geom_errorbar(aes(x=XVAL, ymin = MIN, ymax = MAX), width=0.1,size=0.5) +
    scale_x_continuous("Relative difference (%)", limits = c(-100, 100), breaks=seq(-100, 100, by = 20)) + 
    scale_y_continuous("Fractional count", limits = c(0, .5), breaks=seq(0, 1, by = .1)) + 
    theme_bw() +
    theme(axis.text.x=element_text(size=15),
          axis.text.y=element_text(size=15),
          axis.title.x=element_text(size=18),
          axis.title.y=element_text(size=18))
  
  #ensemble mean
  if (nexp > 1) {
    for (i in 1:nrow(frq_out)) {
      p <- p + geom_point(x=frq_out$XVAL[i],y=frq_out$ENS[i],colour="blue",shape=21,size=3)
    }
    p <- p + geom_line(x=frq_out$XVAL,y=frq_out$ENS,colour="blue",size=0.75)
  }
  
  #print the plot
  png(file_name,width=3500,height=2800,res=300,pointsize=8)
  print(p)
  dev.off()
  
  #return object
  return(frq_out)
}

###
### put together a final figure frame with all details
plot_scatter <- function(fits,scores,file_name="./figures/scattergram/phaseolus_comparable.png") {
  exp_lm <- fits$LM
  exp_rho <- fits$RHO
  nexp <- length(grep("E\\.",names(scores)))
  
  #start the device
  png(file_name,width=2048,height=1500,res=300,pointsize=13)
  
  #0. base graph
  par(mar=c(4,4,1,1))
  plot(0:10,0:10,xlim=c(0,10),ylim=c(0,10),pch=20,col="white",
       xlab="Model",ylab="Expert",font.axis=1,font.lab=2)
  grid()
  
  #1. shading of each expert and of the mean
  if (nexp > 1) {
    for (i in 1:nexp) {
      tpol <- c(exp_lm[[paste("E.",i,sep="")]]$lwr,rev(exp_lm[[paste("E.",i,sep="")]]$upr))
      polygon(x=tpol,y=c(0:15,rev(0:15)),col="#00000040",border=NA)
    }
  }
  
  #2. Shading of mean
  tpol <- c(exp_lm$Expert$lwr,rev(exp_lm$Expert$upr))
  polygon(x=tpol,y=c(0:15,rev(0:15)),col="#0000FF50",border=NA)
  
  #2. any available experts experts (individual lines in gray)
  if (nexp > 1) {
    for (i in 1:nexp) {
      lines(exp_lm[[paste("E.",i,sep="")]]$fit,exp_lm[[paste("E.",i,sep="")]]$eps,col="grey 30",lwd=1.5)
    }
  }
  
  #3. central line that is lm of average
  lines(exp_lm$Expert$fit,exp_lm$Expert$eps,col="blue",lwd=2.5)
  
  #4. points in black for mean of expers
  points(scores$FPS,scores$Expert,pch=20)
  
  #5. points in grey being individual experts
  if (nexp > 1) {
    for (i in 1:nexp) {
      points(scores$FPS,scores[,paste("E.",i,sep="")],col="grey 30",pch=(20+i))
    }
  }
  
  #put some text with the rho values
  if (nexp > 1) {
    rho_m <- round(mean(exp_rho$RHO[1:(nrow(exp_rho)-1)],na.rm=T),2)
    rho_s <- round(sd(exp_rho$RHO[1:(nrow(exp_rho)-1)],na.rm=T),2)
    wtext <- substitute(paste(rho,plain(" = "),rhm,plain(" (+/- "),rhs,plain(")"),),list(rhm=rho_m,rhs=rho_s))
    text(0,9.5,cex=1,pos=4,wtext)
  } else {
    rho_m <- round(exp_rho$RHO[1],na.rm=T,2)
    if (exp_rho$PVAL[1] > .1) {
      signif <- "NS"
    } else if (exp_rho$PVAL[1] <= .1 & exp_rho$PVAL[1] > 0.05) {
      signif <- "*"
    } else if (exp_rho$PVAL[1] <= .05 & exp_rho$PVAL[1] > 0.01) {
      signif <- "**"
    } else if (exp_rho$PVAL[1] <= .01) {
      signif <- "***"
    }
    wtext <- substitute(paste(rho,plain(" = "),rhm,plain(" "),sgn),list(rhm=rho_m,sgn=signif))
    text(0,9.5,cex=1,pos=4,wtext)
  }
  dev.off()
  return(file_name)
}

###
### compute the models for each expert and for consensus
fit_reg <- function(scores) {
  nexp <- length(grep("E\\.",names(scores)))
  
  #if > 1 expert, glm of avg + each expert (shading is max/min amongst experts pred +- CI)
  #else regression of only expert and shading is C.I.
  exp_lm <- list()
  exp_rho <- data.frame()
  if (nexp > 1) {
    for (i in 1:nexp) {
      #i <- 1
      fitdata <- data.frame(fps=scores$FPS,eps=scores[,paste("E.",i,sep="")])
      fitdata <- fitdata[which(!is.na(fitdata$fps)),]
      fitdata <- fitdata[which(!is.na(fitdata$eps)),]
      
      if (nrow(fitdata)>2) {
        mod <- lm(fps ~ eps,data=fitdata)
        fps_p <- predict(mod,newdata=data.frame(eps=0:15),interval="predict",level=0.2)
        fps_p <- as.data.frame(cbind(eps=0:15,fps_p))
        exp_lm[[paste("E.",i,sep="")]] <- fps_p
        rho <- cor.test(fitdata$fps,fitdata$eps,method="spearman")
        exp_rho <- rbind(exp_rho,data.frame(EXPERT=i,RHO=rho$estimate,PVAL=rho$p.value))
      } else {
        fps_p <- as.data.frame(cbind(eps=0:15,fit=NA,lwr=NA,upr=NA))
        exp_lm[[paste("E.",i,sep="")]] <- fps_p
        exp_rho <- rbind(exp_rho,data.frame(EXPERT=i,RHO=NA,PVAL=NA))
      }
    }
    
    #fit of average of both experts
    fitdata <- data.frame(fps=scores$FPS,eps=scores$Expert)
    fitdata <- fitdata[which(!is.na(fitdata$fps)),]
    fitdata <- fitdata[which(!is.na(fitdata$eps)),]
    
    if (nrow(fitdata)>2) {
      mod <- lm(fps ~ eps,data=fitdata)
      fps_p <- predict(mod,newdata=data.frame(eps=0:15),interval="predict",level=0.2)
      fps_p <- as.data.frame(cbind(eps=0:15,fps_p))
      exp_lm[["Expert"]] <- fps_p
      rho <- cor.test(fitdata$fps,fitdata$eps,method="spearman")
      exp_rho <- rbind(exp_rho,data.frame(EXPERT="Mean",RHO=rho$estimate,PVAL=rho$p.value))
    } else {
      fps_p <- as.data.frame(cbind(eps=0:15,fit=NA,lwr=NA,upr=NA))
      exp_lm[["Expert"]] <- fps_p
      exp_rho <- rbind(exp_rho,data.frame(EXPERT="Mean",RHO=NA,PVAL=NA))
    }
  } else {
    fitdata <- data.frame(fps=scores$FPS,eps=scores[,"E.1"])
    fitdata <- fitdata[which(!is.na(fitdata$fps)),]
    fitdata <- fitdata[which(!is.na(fitdata$eps)),]
    
    if (nrow(fitdata)>2) {
      mod <- lm(fps ~ eps,data=fitdata)
      fps_p <- predict(mod,newdata=data.frame(eps=0:15),interval="predict",level=0.2)
      fps_p <- as.data.frame(cbind(eps=0:15,fps_p))
      exp_lm[["E.1"]] <- fps_p; exp_lm[["Expert"]] <- fps_p
      rho <- cor.test(fitdata$fps,fitdata$eps,method="spearman")
      exp_rho <- rbind(exp_rho,data.frame(EXPERT=i,RHO=rho$estimate,PVAL=rho$p.value))
      exp_rho <- rbind(exp_rho,data.frame(EXPERT="Mean",RHO=rho$estimate,PVAL=rho$p.value))
    } else {
      fps_p <- as.data.frame(cbind(eps=0:15,fit=NA,lwr=NA,upr=NA))
      exp_lm[["E.1"]] <- fps_p; exp_lm[["Expert"]] <- fps_p
      exp_rho <- rbind(exp_rho,data.frame(EXPERT=i,RHO=NA,PVAL=NA))
      exp_rho <- rbind(exp_rho,data.frame(EXPERT="Mean",RHO=NA,PVAL=NA))
    }
  }
  out_obj <- list(LM=exp_lm,RHO=exp_rho)
  return(out_obj)
}


###
#function to load expert scores and pre-process them so they are ready for regressions
prepare_exp <- function(ex_file="./expert/res_phaseolus.tab",ex_score="Comparable",rMod) {
  #expert data
  rExp <- read.table(ex_file,header=T,sep="\t")
  
  #count experts
  nexp <- nrow(rExp)
  
  #reorganise
  wcol <- grep(paste(ex_score,".",sep=""),names(rExp))
  tExp <- rExp[,wcol]
  tExp <- as.data.frame(t(tExp))
  tExp <- cbind(Taxa=row.names(tExp),tExp); row.names(tExp) <- 1:nrow(tExp)
  names(tExp)[2:ncol(tExp)] <- paste("E.",1:(ncol(tExp)-1),sep="")
  
  #formatting names
  tExp$Taxa <- sapply(tExp$Taxa,FUN=function(x) {yr<-gsub(paste(ex_score,".Expert.Priority.Score..",sep=""),"",paste(x));yr <- substr(yr,1,(nchar(yr)-1));return(yr)})
  for (i in 1:nexp) {tExp[,(i+1)] <- sapply(tExp[,(i+1)],FUN=function(x) {yr<-gsub("High Priority: ","",paste(x)); yr<-gsub("No Need for Further Collection: ","",yr); yr<-gsub("N/A",NA,yr); return(yr)})}
  for (i in 1:nexp) {tExp[,(i+1)] <- as.numeric(tExp[,(i+1)])}
  
  #merge the two tables (with tExp as basis)
  scores <- merge(tExp,rMod,by="Taxa",all.x=T,all.y=F)
  scores$TAXON_nameinga <- NULL; scores$crop_code <- NULL; scores$notes <- NULL
  
  #calculate RDs
  for (i in 1:nexp) {
    scores$VAL <- (scores$FPS - scores[,paste("E.",i,sep="")]) * 10
    names(scores)[ncol(scores)] <- paste("RD.",i,sep="")
  }
  
  #if more than one expert then do avg
  if (nexp > 1) {
    scores$Expert <- rowMeans(scores[,grep("E.",names(scores))],na.rm=T)
  } else {
    scores$Expert <- scores$E.1
  }
  scores$RD <- (scores$FPS - scores$Expert) * 10 #RD of avg
  return(scores)
}

