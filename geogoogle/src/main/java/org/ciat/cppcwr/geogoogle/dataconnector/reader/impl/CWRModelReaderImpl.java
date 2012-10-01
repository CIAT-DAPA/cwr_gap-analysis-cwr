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
package org.ciat.cppcwr.geogoogle.dataconnector.reader.impl;

import org.ciat.cppcwr.geogoogle.config.GeoGoogleModule;
import org.ciat.cppcwr.geogoogle.dataconnector.reader.DataModelReader;
import org.ciat.cppcwr.geogoogle.db.impl.MySQLDataBaseManager;

import sun.swing.text.CountingPrintable;

import com.google.inject.Guice;
import com.google.inject.Injector;

import java.sql.Connection;
import java.sql.ResultSet;
import java.util.ArrayList;

/**
 * @author Héctor Tobón (htobon)
 * 
 */
public class CWRModelReaderImpl implements DataModelReader {

	private final String TABLE_NAME = "raw_occurrences";
	private final String COUNTRY_FIELD_NAME = "country";
	private final String ADM1_FIELD_NAME = "adm1";
	private final String ADM2_FIELD_NAME = "adm2";
	private final String ADM3_FIELD_NAME = "adm3";
	private final String LOCAL_AREA_FIELD_NAME = "local_area";
	private final String LOCALITY_FIELD_NAME = "locality";
	private final String COORD_FIELD_NAME = "coord";
	private final String ID_FIELD_NAME = "id";
	private final String LATTITUDE = "latitude";
	private final String LONGITUDE = "longitude";
	private final String GEOREF_FLAG = "georef_flag";
	/*--------------------------------------------------*/
	private MySQLDataBaseManager dm;

	public CWRModelReaderImpl() {
		Injector inject = Guice.createInjector(new GeoGoogleModule());
		dm = inject.getInstance(MySQLDataBaseManager.class);
	}

	/*
	 * Get data from database
	 * @return ArrayList<String[]> queries to geocoding (location values)
	 * */
	public ArrayList<String[]> getDBData(String crit_gen) {
		ArrayList<String[]> data = new ArrayList<String[]>();
		Connection connection = dm.openConnection();

		if (connection != null) {
			String query = "SELECT " + ID_FIELD_NAME + "," + COUNTRY_FIELD_NAME
					+ "," + ADM1_FIELD_NAME + "," + ADM2_FIELD_NAME + ","
					+ ADM3_FIELD_NAME + "," + LOCAL_AREA_FIELD_NAME + ","
					+ LOCALITY_FIELD_NAME + "  FROM " + TABLE_NAME + " WHERE "
					+ COUNTRY_FIELD_NAME + " IS NOT NULL AND "
					+ LATTITUDE + " IS NULL  AND " + LONGITUDE + " IS NULL AND "+GEOREF_FLAG+" = 0 AND x1_genus = '" + crit_gen + "';"; // Only records with latitude and longitude values and no georef_flag = 1 
			ResultSet rs = dm.makeQuery(query, connection);

			System.out.println(query);
			
			try {
				if (rs != null) { 
					String[] array = null;
					while (rs.next()) {
						array = new String[2];
						array[0] = rs.getString(ID_FIELD_NAME);
						array[1] = "";
						
						// Generate a location query to geocoding
						if(rs.getString(COUNTRY_FIELD_NAME) != null){
							array[1] += rs.getString(COUNTRY_FIELD_NAME).trim().replace(" ","+") + ",+";
						}
						
						if(rs.getString(ADM1_FIELD_NAME) != null){
							array[1] += rs.getString(ADM1_FIELD_NAME).trim().replace(" ","+") + ",+";
						}
						
						if(rs.getString(ADM2_FIELD_NAME) != null){
							array[1] += rs.getString(ADM2_FIELD_NAME).trim().replace(" ","+") + ",+";
						}
						
						if(rs.getString(ADM3_FIELD_NAME) != null){
							array[1] += rs.getString(ADM3_FIELD_NAME).trim().replace(" ","+") + ",+";
						}
						
						if(rs.getString(LOCAL_AREA_FIELD_NAME) != null){
							array[1] += rs.getString(LOCAL_AREA_FIELD_NAME).trim().replace(" ","+") + ",+";
						}
							
						if(rs.getString(LOCALITY_FIELD_NAME) != null){
							array[1] += rs.getString(LOCALITY_FIELD_NAME).trim().replace(" ","+");
						}
						
						data.add(array);
						array = null;
					}
				}
			} catch (Exception ex) {
				ex.printStackTrace();
			}
		}

		return data;
	}

}
