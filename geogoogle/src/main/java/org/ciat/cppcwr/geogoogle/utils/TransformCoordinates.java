package org.ciat.cppcwr.geogoogle.utils;

import java.util.StringTokenizer;

/*
 * Transform cardinal coordinates to hexadecimal coordinates
*/
public class TransformCoordinates {
	
	final static String DELIM =  " ";
	
	public static double[] getDecimalCoordinates(String coord){
		double[] coordValues = new double[2];
		StringTokenizer token = new StringTokenizer(coord," ");// for each letter
		
		if(token.countTokens() == 2){
			for(int i = 0;i < token.countTokens();i++){
				coordValues[i] = getDecimalValue(token.nextToken());
			}
		}else{
			System.out.println("Error: Invalid coordinate");
		}
		
		return coordValues;
	}

	private static double getDecimalValue(String str) {
		StringTokenizer token = new StringTokenizer(str, DELIM);// Delim.
		double result = Double.parseDouble(token.nextToken())
				+ (Double.parseDouble(token.nextToken()) / 60)
				+ (Double.parseDouble(token.nextToken()) / 3600);
		return result;
	}

}
