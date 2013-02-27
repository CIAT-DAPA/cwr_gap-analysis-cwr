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

import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.StringTokenizer;

/**
 * @author Héctor Tobón (htobon)
 * 
 */
public class CWRModelReaderImpl implements DataModelReader {

	/*------------Fields from File-----------------------*/
	private final String COUNTRY = "country";
	private final String ADM1_FILE = "state/province";
	private final String ADM2_FILE = "adm2";
	private final String ADM3_FILE = "adm3";
	private final String LOCALITY = "locality";
	private final String COUNTY = "county";
	private final String LATITUDE_FILE = "latitude";
	private final String LONGITUDE_FILE = "longitude";
	/*------------Fields from DB-------------------------*/
	private final String TABLE_NAME = "raw_occurrences";
	private final String COUNTRY_FIELD_NAME = "final_country";
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
	private final String[] DATA = {"to","from","just","of","edge","water","within"};
	private MySQLDataBaseManager dm;
	private String query = "SELECT " + ID_FIELD_NAME + "," + COUNTRY_FIELD_NAME
			+ "," + ADM1_FIELD_NAME + "," + ADM2_FIELD_NAME + ","
			+ ADM3_FIELD_NAME + "," + LOCAL_AREA_FIELD_NAME + ","
			+ LOCALITY_FIELD_NAME + "  FROM " + TABLE_NAME + " WHERE "
			+ COUNTRY_FIELD_NAME + " IS NOT NULL ";

	public CWRModelReaderImpl() {
		Injector inject = Guice.createInjector(new GeoGoogleModule());
		dm = inject.getInstance(MySQLDataBaseManager.class);
	}

	/*
	 * Get data from database
	 * 
	 * @return ArrayList<String[]> queries to geocoding (location values)
	 */
	public ArrayList<String[]> getDBData(String crit_gen) {
		ArrayList<String[]> data = new ArrayList<String[]>();
		Connection connection = dm.openConnection();

		if (connection != null) {
			if (!crit_gen.toLowerCase().equals("-all")) {
				query += "AND x1_genus = '" + crit_gen + "';"; // Only records
																// with latitude
																// and longitude
																// values and no
																// georef_flag =
																// 1
			} else {
				query += ";";
			}

			ResultSet rs = dm.makeQuery(query, connection);

			try {
				if (rs != null) {
					String[] array = null;
					while (rs.next()) {
						array = new String[2];
						array[0] = rs.getString(ID_FIELD_NAME);
						array[1] = "";

						// Generate a location query to geocoding
						if (rs.getString(COUNTRY_FIELD_NAME) != null) {
							array[1] += rs.getString(COUNTRY_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(ADM1_FIELD_NAME) != null) {
							array[1] += rs.getString(ADM1_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(ADM2_FIELD_NAME) != null) {
							array[1] += rs.getString(ADM2_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(ADM3_FIELD_NAME) != null) {
							array[1] += rs.getString(ADM3_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(LOCAL_AREA_FIELD_NAME) != null) {
							array[1] += rs.getString(LOCAL_AREA_FIELD_NAME)
									.trim().replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(LOCALITY_FIELD_NAME) != null) {
							array[1] += rs.getString(LOCALITY_FIELD_NAME)
									.trim().replace(" ", "+");
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

	public ArrayList<String[]> getDBData(int option) {
		ArrayList<String[]> data = new ArrayList<String[]>();
		Connection connection = dm.openConnection();

		if (connection != null) {
			if (option == 0) {
				query += " AND x1_genus IN ('Aegilops','Amblyopyrum','Avena','Cajanus','Cicer','Daucus','Eleusine','Ensete','Helianthus','Hordeum','Ipomoea','Lathyrus','Lens','Malus','Medicago','Musa','Oryza','Pennisetum','Phaseolus','Pisum','Secale','Solanum','Sorghum','Triticum','Vavilovia','Vicia','Vigna');";
			} else if (option == 1) {
				query += " AND x1_genus NOT IN ('Aegilops','Amblyopyrum','Avena','Cajanus','Cicer','Daucus','Eleusine','Ensete','Helianthus','Hordeum','Ipomoea','Lathyrus','Lens','Malus','Medicago','Musa','Oryza','Pennisetum','Phaseolus','Pisum','Secale','Solanum','Sorghum','Triticum','Vavilovia','Vicia','Vigna');";
			} else if (option == 2) {
				query += " AND filename IN ('raw_BD_digitization_CAS_SC.xls','raw_BD_digitization_PH_SC.xls')";
			}
			
			System.out.println(query);
			ResultSet rs = dm.makeQuery(query, connection);

			try {
				if (rs != null) {
					String[] array = null;
					while (rs.next()) {
						array = new String[2];
						array[0] = rs.getString(ID_FIELD_NAME);
						array[1] = "";

						// Generate a location query to geocoding
						if (rs.getString(COUNTRY_FIELD_NAME) != null) {
							array[1] += rs.getString(COUNTRY_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(ADM1_FIELD_NAME) != null) {
							array[1] += rs.getString(ADM1_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(ADM2_FIELD_NAME) != null) {
							array[1] += rs.getString(ADM2_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(ADM3_FIELD_NAME) != null) {
							array[1] += rs.getString(ADM3_FIELD_NAME).trim()
									.replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(LOCAL_AREA_FIELD_NAME) != null) {
							array[1] += rs.getString(LOCAL_AREA_FIELD_NAME)
									.trim().replace(" ", "+")
									+ ",+";
						}

						if (rs.getString(LOCALITY_FIELD_NAME) != null) {
							array[1] += rs.getString(LOCALITY_FIELD_NAME)
									.trim().replace(" ", "+");
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

	@Override
	public ArrayList<String[]> getFileData(String url) {
		System.out.println("Data from file");
		ArrayList<String[]> data = new ArrayList<String[]>();
		
		try {
			FileInputStream file = new FileInputStream(url);
			DataInputStream in = new DataInputStream(file);
			BufferedReader br = new BufferedReader(new InputStreamReader(in));
			String strLine;
			int temp = 0;
			int cont = 0;
			boolean header_flag = true;
			ArrayList<String> header = new ArrayList();
			
			System.out.println("Extrayendo informacion del archivo ......");
			while ((strLine = br.readLine()) != null) {
				StringTokenizer token = new StringTokenizer(strLine, "|");
				if (header_flag) {
					while (token.hasMoreTokens()) {
						header.add(token.nextToken());
					}
					header_flag = false;
				} else {
					String[] record_value = strLine.split("\\|");
					data.add(record_value);
				}
				temp++;
				cont++;
			}
			
			int index_id = -1;
			int index_country = -1;
			int index_adm1 = -1;
			int index_adm2 = -1;
			int index_adm3 = -1;
			int index_locality = -1;
			int index_county = -1;
			int index_latitude = -1;
			int index_longitude = -1;

			for (int i = 0;i < header.size();i++) { // Capturando los indices del archivo para tomar la informacion
				if(header.get(i).toLowerCase().equals(ID_FIELD_NAME)){
					index_id = i;
				}
				if(header.get(i).toLowerCase().equals(COUNTRY)){
					index_country = i;
				}
				if(header.get(i).toLowerCase().equals(ADM1_FILE)){
					index_adm1 = i;
				}
				if(header.get(i).toLowerCase().equals(ADM2_FIELD_NAME)){
					index_adm2 = i;
				}
				if(header.get(i).toLowerCase().equals(ADM3_FIELD_NAME)){
					index_adm3 = i;
				}
				if(header.get(i).toLowerCase().equals(LOCALITY)){
					index_locality = i;
				}
				if(header.get(i).toLowerCase().equals(COUNTY)){
					index_county = i;
				}
				if(header.get(i).toLowerCase().equals(LATITUDE_FILE)){
					index_latitude = i;
				}
				if(header.get(i).toLowerCase().equals(LONGITUDE_FILE)){
					index_longitude = i;
				}
			}
			
			
			int count_latitude = 0;
			int count_longitude = 0;
			int count_georef_ps = 0;
			int count_latitude_incorrect = 0;
			int count_longitude_incorrect = 0;
			
			System.out.println("Evaluando la informacion del archivo.........................");
			for (int i =  0;i <  data.size();i++){ // Tomando la informacion para establecer cuales se de los registros se pueden georeferenciar, cuales tienen valores para latitude y longitud. 	
				if(data.get(i)[index_latitude] != null && !data.get(i)[index_latitude].equals("0")) {
					if(!data.get(i)[index_latitude].matches("\\d{2}/\\d{2}/\\d{2}")){
						try{
							if(Double.parseDouble(data.get(i)[index_latitude]) >= -90.0 && Double.parseDouble(data.get(i)[index_latitude]) <= 90.0){
								count_latitude++;
							}else{
								count_latitude_incorrect++;
							}
						}catch(NumberFormatException e){
							count_latitude_incorrect++;
						}
					}else {
						count_latitude_incorrect++;
					}
				}
				if(data.get(i)[index_longitude] != null && !data.get(i)[index_longitude].equals("0")){
					if(!data.get(i)[index_latitude].matches("\\d{2}/\\d{2}/\\d{2}")){	
						try{	
							if(Double.parseDouble(data.get(i)[index_longitude]) >= -180.0 && Double.parseDouble(data.get(i)[index_longitude]) <= 180.0){
								count_longitude++;
							}else{
								count_longitude_incorrect++;
							}
						}catch(NumberFormatException e){
							count_latitude_incorrect++;
						}
					}else {
						count_latitude_incorrect++;
					}
				}
				if(!data.get(i)[index_country].equals("") && !data.get(i)[index_adm1].equals("") && !data.get(i)[index_county].equals("")) {
					count_georef_ps++;
				}
			}
			
			
			 FileWriter fichero = null;
		     PrintWriter pw = null;
		        try
		        {
		        	System.out.println("Creando el archivo de resultados..............");
		            fichero = new FileWriter("Informe_Archivo_"+getFileName(url)+".txt");
		            pw = new PrintWriter(fichero);

		            pw.println("-----------------FILE INFORMATION---------------------");
		            pw.println("N. records with latitude value -> "+count_latitude);
		            pw.println("N. records with longitude value -> "+count_longitude);
		            pw.println("N. records with georef possibilities -> "+georefPossibilities(count_georef_ps));
		            pw.println("N. records without lat. value -> "+(data.size() - (count_latitude+count_latitude_incorrect)));
		            pw.println("N. records without lon. value -> "+(data.size() - (count_longitude+count_longitude_incorrect)));
		            pw.println("-------------------POST SCRIPT-------------------------");
		            pw.println("N. records with wrong latitude value -> "+count_latitude_incorrect);
		            pw.println("N. records with wrong longitude value -> "+count_longitude_incorrect);

		        } catch (Exception e) {
		            e.printStackTrace();
		        } finally {
		           try {
		           // Nuevamente aprovechamos el finally para 
		           // asegurarnos que se cierra el fichero.
		           if (null != fichero)
		              fichero.close();
		           } catch (Exception e2) {
		              e2.printStackTrace();
		           }
		        }
			
			in.close();
			
			ArrayList<String[]> dataFinal = new ArrayList<String[]>();
			System.out.println("Creando consultas para google ...............");
			
			for(int i = 0;i < data.size();i++) {
				String[] array = new String[2];
				String query = "";
				array[0] = data.get(i)[index_id];

					if(index_country >= 0){
						if (data.get(i)[index_country] != null && !data.get(i)[index_country].equals("")){
							query += data.get(i)[index_country].trim()
								+ "+";
						}
					}
						
					if(index_adm1 >= 0){
						if(data.get(i)[index_adm1] != null && !data.get(i)[index_adm1].equals("")) {
							query += data.get(i)[index_adm1].trim()
								+ "+";
						}
					}
					
					if(index_adm2 >= 0 ) {
						if (data.get(i)[index_adm2] != null && !data.get(i)[index_adm2].equals("")) {
							query += data.get(i)[index_adm2].trim() + "+";
						}
					}
					
					if(index_adm3 >= 0) {
						if (data.get(i)[index_adm3] != null && !data.get(i)[index_adm3].equals("")) {
							query += data.get(i)[index_adm3].trim() + "+";
						}
					}
					
					if(index_county >= 0 ){
						if (data.get(i)[index_county] != null && !data.get(i)[index_county].equals("")) {
							query += data.get(i)[index_county].trim()+"+";
						}
					}
					
					if(index_locality >= 0){
						if (data.get(i)[index_locality] != null && !data.get(i)[index_locality].equals("")){
							query += getFixedLocality(data.get(i)[index_locality]).trim()
								+ "+";
						}
					}
					
				
				array[1] = query;
				dataFinal.add(array);
			}
			
			return dataFinal;
			
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return null;
		}
	
	}
	
	private String getFileName (String url) {
		String[] url_windows = url.split("\\/");
		String[] url_linux = url.split("\\|");
		int pos_filename = 0;
		
		if (url_windows.length > 1) { // Trabajar con url de windows
			pos_filename = url_windows.length - 1;
			return url_windows[pos_filename];
		}else if (url_linux.length > 1) { // Trabajar con url de Linux
			pos_filename = url_linux.length - 1;
			return url_linux[pos_filename];
		}else {
			System.out.println("Error: la estructura de la url no ha podido ser identificada");
			return null;
		}
	}

	public double georefPossibilities(int count_georef) {
		return Math.ceil(count_georef * 0.35);
	}
	
	private String getFixedLocality (String locality) {
		locality += "+";
		locality = locality.replace("++","");
		String[] array_semicolon =  locality.trim().split(";");
		String[] array_dot = locality.trim().split(".");
		
		if(array_semicolon.length > 0){
			String[] array_to_return =  array_semicolon[0].split(" ");
			if(array_to_return.length > 3){
				return array_to_return[0]+" "+array_to_return[1]+" "+array_to_return[2];
			}else{
				return array_semicolon[0];
			}
		}else if(array_dot[0].length() > 0){
			String[] array_to_return =  array_dot[0].split(" ");
			if(array_to_return.length > 3){
				return array_to_return[0]+" "+array_to_return[1]+" "+array_to_return[2];
			}else{
				return array_dot[0];
			}
		}else {
			return locality;
		}
		
	}
	
	private int findValueData(String cad){
		int index = -1;
			for (int i=0;i < DATA.length	;i++) {
				if (DATA[i].equals(cad)) {
					index = i;
				}
			}
		return index;
	}
	
	private boolean isBadValue(String value) {
		int n = -1;
		try{
		   n = Integer.parseInt(value); // Tomando el valor y evaluando si es un numero, en caso de serlo no es valido
		}catch(Exception e){
		}
		
		if (n >= 0) { // El dato que se ha obtenido es numerico, tiene el mismo valor asignado en el inicio
			return true;
		}
		
		if (findValueData(value) > 0) {
			return true;
		}
		
		return false;
	}
}
