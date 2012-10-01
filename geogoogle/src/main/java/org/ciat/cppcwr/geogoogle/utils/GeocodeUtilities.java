package org.ciat.cppcwr.geogoogle.utils;

import java.net.URLEncoder;
import java.util.ArrayList;

/**
 * Utilities set for Geocoding process
 * 
 * @author Alex Castañeda
 * 
 */
public class GeocodeUtilities {
	
	// Take less precision to geocoding
	public static String lessLocationValues(String query) {
		String[] array = query.split("+");
		String result = "";

		for (int i = 0; i < (array.length) - 1; i++) {
			result += array[i] + "+";
		}

		return result;
	}

	/* Get Ids from location List */
	public static ArrayList<String> returnIds(ArrayList<String[]> dataLocationList) {
		ArrayList<String> ids = new ArrayList<String>();

		for (int i = 0; i < dataLocationList.size(); i++) {
			ids.add(dataLocationList.get(i)[0]);
		}

		return ids;
	}

	// Get the distance to 2 coordinates (km)
	public static double getDistance(double[] coord1, double[] coord2) {
		double d = 0;
		if (coord1.length == 2 && coord2.length == 2) {
			double LatA = (coord1[0] * Math.PI) / 180;
			double LatB = (coord2[0] * Math.PI) / 180;
			double LngA = (coord1[1] * Math.PI) / 180;
			double LngB = (coord2[1] * Math.PI) / 180;

			d = 6371 * Math.acos(Math.cos(LatA) * Math.cos(LatB)
					* Math.cos(LngB - LngA) + Math.sin(LatA) * Math.sin(LatB));
			return d; // Retorna la distancia en kilometros
		} else {
			System.out.println("Error: coord length is not correct");
			return -1;
		}
	}

	/* Remove any strange value from the query */
	public static ArrayList<String> transformToValidQuery(
			ArrayList<String[]> dataLocationList) {
		ArrayList<String> queries = new ArrayList<String>();

		for (int i = 0; i < dataLocationList.size(); i++) {
			queries.add("address="
					+ URLEncoder.encode(dataLocationList.get(i)[1]) + "%");
		}

		return queries;
	}

}
