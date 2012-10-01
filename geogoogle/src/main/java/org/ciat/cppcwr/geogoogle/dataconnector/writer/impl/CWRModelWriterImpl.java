/**
 * Copyright 2012 International Center for Tropical Agriculture (CIAT).
 * 
 * This file is part of: 
 * "GeoGoogle - Collecting Protecting and Preparing Crop Wild Relatives"
 * 
 * GeoGoogle is free software: You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * at your option) any later version.
 * 
 * GeoGoogle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * See <http://www.gnu.org/licenses/>.
 */
package org.ciat.cppcwr.geogoogle.dataconnector.writer.impl;

import java.util.ArrayList;

import org.ciat.cppcwr.geogoogle.config.GeoGoogleModule;
import org.ciat.cppcwr.geogoogle.dataconnector.writer.DataModelWriter;
import org.ciat.cppcwr.geogoogle.db.impl.MySQLDataBaseManager;

import com.google.inject.Guice;
import com.google.inject.Injector;
import com.google.inject.Singleton;

import java.io.FileWriter;
import java.io.PrintWriter;
import java.sql.Connection;

/**
 * @author Hector Tobón (htobon)
 * 
 */

@Singleton
public class CWRModelWriterImpl implements DataModelWriter {

	private final String TABLE_NAME = "raw_occurrences";
	private final String COORD_FIELD_NAME = "coord";
	private final String LATTITUDE = "latitude_georef";
	private final String LONGITUDE = "longitude_georef";
	private final String DISTANCE = "distance_georef";
	private final String ID_FIELD_NAME = "id";
	private final String GEOREF_FLAG = "georef_flag";
	/*------------------------------------------------------*/
	private MySQLDataBaseManager dm;

	public CWRModelWriterImpl() {
		Injector inject = Guice.createInjector(new GeoGoogleModule());
		dm = inject.getInstance(MySQLDataBaseManager.class);
	}

	/**
	 * Write the results into file
	 */
	public boolean writeCoordValuesInFile(double[] coord, String locationType,
			double distance, String idOccurrence, String filename) {
		String data = idOccurrence + "\t" + coord[0] + "\t" + coord[1] + "\t"
				+ distance;
		FileWriter file = null;
		PrintWriter pw = null;

		try {
			file = new FileWriter(filename, true);
			pw = new PrintWriter(file);
			pw.println(data);
		} catch (Exception e) {
			return false;
		} finally {
			try {
				if (file != null) {
					file.close();
				}
			} catch (Exception ex) {
				ex.printStackTrace();
			}
		}

		return true;
	}

	public boolean writeCoordValues(ArrayList<double[]> coordList,
			ArrayList<String> locationTypeList, double distance,
			ArrayList<int[]> idOccurrencesList) {
		String updateQuery = "";

		for (int i = 0; i < idOccurrencesList.size(); i++) {
			for (int j = 0; j < idOccurrencesList.get(i).length; j++) {
				updateQuery += "UPDATE " + TABLE_NAME + " SET " + LATTITUDE
						+ " = " + coordList.get(i)[0] + "," + LONGITUDE + " = "
						+ coordList.get(i)[1] + "," + DISTANCE + " = "
						+ distance + ", " + GEOREF_FLAG + " = 1" +
						" WHERE " + ID_FIELD_NAME + " = "
						+ idOccurrencesList.get(i)[j] + ";";
			}
		}

		Connection connection = dm.openConnection();

		if (connection != null) {
			int affected_rows = dm.makeChange(updateQuery, connection);
			dm.closeConnection(connection);
			if (affected_rows != -1) {
				System.out
						.println("SUCCESS: Affected rows => " + affected_rows);
				return true;
			} else {
				System.out.println("ERROR: No changes was taken to "
						+ TABLE_NAME);
				return false;
			}
		} else {
			System.out
					.println("ERROR: Connection error, please check the data base configuration");
			return false;
		}
	}
	
	/*
	 * Write a single database record
	 */
	public boolean writeCoordValues(double[] coord, String locationType,
			double distance, String idOccurrence) {
		String updateQuery = "";

		updateQuery += "UPDATE " + TABLE_NAME + " SET " + LATTITUDE + " = "
				+ coord[0] + "," + LONGITUDE + " = " + coord[1] + ","
				+ DISTANCE + " = " + distance + ", " +  GEOREF_FLAG + " = 1 WHERE " + ID_FIELD_NAME
				+ " = " + idOccurrence + ";";

			Connection connection = dm.openConnection(); // Get database
															// connection

			if (connection != null) { // if connect
				int affected_rows = dm.makeChange(updateQuery, connection); // Number
																			// of
																			// affected
																			// rows
																			// on
																			// db
				dm.closeConnection(connection); //  Close BD Connection
				if (affected_rows != -1) { // No errors
					System.out.println("SUCCESS: Affected rows => "
							+ affected_rows);
					return true;
				} else {
					System.out.println("ERROR: No changes was taken to "
							+ TABLE_NAME);
					return false;
				}
			} else {
				System.out
						.println("ERROR: Connection error, please check the data base configuration");
				return false;
			}
		
	}
	
	
	/* Change Flag Status field */
	public boolean changeGeorefFlagStatus(String idOccurrence){
		String updateQuery = "";

		updateQuery += "UPDATE " + TABLE_NAME + " SET " + GEOREF_FLAG + " = 1 WHERE " + ID_FIELD_NAME
				+ " = " + idOccurrence + ";";


			Connection connection = dm.openConnection(); // Get database
															// connection

			if (connection != null) { // if connect
				int affected_rows = dm.makeChange(updateQuery, connection); // Number
																			// of
																			// affected
																			// rows
																			// on
																			// db
				dm.closeConnection(connection);
				if (affected_rows != -1) { // No errors
					return true;
				} else {
					return false;
				}
			} else {
	
				return false;
			}
			
	}

}
