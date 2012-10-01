package org.ciat.cppcwr.geogoogle.utils;

public class Validator {
	public static boolean isString(String str){
		if(!str.matches("[W]")){
			return true;
		}else{
			return false;
		}
	}
}
