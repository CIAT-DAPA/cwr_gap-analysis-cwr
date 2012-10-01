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
package org.ciat.cppcwr.geogoogle.service;

import java.io.Console;
import java.net.InetSocketAddress;
import java.net.Proxy;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.ciat.cppcwr.geogoogle.config.GeoGoogleModule;
import org.ciat.cppcwr.geogoogle.utils.UrlSignerGenerator;
import org.ciat.cppcwr.geogoogle.utils.Validator;
import org.ciat.cppcwr.geogoogle.dataconnector.reader.DataModelReader;
import org.ciat.cppcwr.geogoogle.dataconnector.writer.DataModelWriter;
import org.ciat.cppcwr.geogoogle.utils.GeocodeUtilities;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import com.google.inject.Guice;
import com.google.inject.Injector;
import com.google.inject.Inject;


public class GeoGoogle {

	@Inject
	private DataModelReader mr;

	@Inject
	private DataModelWriter mw;

	private final String URL_SEND = "https://maps.googleapis.com/maps/api/geocode/xml?sensor=false&";
	private final String OPTION_DATABASE = "1";
	private final String OPTION_FILE = "2";
	private final static String OPTION_NO_PREMIUM = "-np";
	private double THRESHOLD = 10;
	private Console console = System.console();

	/*
	 * Update data at bd
	 */
	public void init(String crit_gen) {// Critical genus

		System.out.println("Genus: "+ crit_gen);
		
		Injector inject = Guice.createInjector(new GeoGoogleModule());
		UrlSignerGenerator usg = inject.getInstance(UrlSignerGenerator.class);
		mr = inject.getInstance(DataModelReader.class);
		mw = inject.getInstance(DataModelWriter.class);

		ArrayList<String[]> data = mr.getDBData(crit_gen); // Get data by priority genus
		System.out.println("Data Size : " + data.size());
		ArrayList<String> queries = GeocodeUtilities.transformToValidQuery(data);
		ArrayList<String> ids = GeocodeUtilities.returnIds(data);
		String user_dec = console
				.readLine("OPTIONS \n [1] Save into database \n [2] Save into a file \n Select your option: ");

		String filename = "";
		if (user_dec.equals(OPTION_FILE)) {
			filename = console
					.readLine("Enter a filename: ");
		}

		try {
			for (int k = 0; k < queries.size(); k++) {
				URL url = new URL(URL_SEND + queries.get(k));
				URL file_url = new URL(url.getProtocol() + "://"
						+ url.getHost()
						+ usg.signRequest(url.getPath(), url.getQuery()));

				// Get information from URL
				DocumentBuilderFactory dbf = DocumentBuilderFactory
						.newInstance();
				// Create a proxy to work in CIAT (erase this in another place)
				Proxy proxy = new Proxy(Proxy.Type.HTTP, new InetSocketAddress(
						"proxy2.ciat.cgiar.org", 8080));
				DocumentBuilder db = dbf.newDocumentBuilder();

				Document doc = db.parse(file_url.openConnection(proxy)
						.getInputStream());// Document
											// with
											// data

				if (doc != null) {// Don't make it empty document
					NodeList locationList = doc
							.getElementsByTagName("location");
					NodeList locationTypeList = doc
							.getElementsByTagName("location_type");
					NodeList viewPortList = doc
							.getElementsByTagName("viewport");

					Node location = null, lat = null, lng = null;
					if (locationList.getLength() > 0) {
						for (int i = 0; i < locationList.getLength(); i++) {
							location = locationList.item(i);

							if (location.hasChildNodes()) {
								lat = location.getChildNodes().item(1);
								lng = location.getChildNodes().item(3);
							}
						}

						Node locationType = null;
						if (locationTypeList.getLength() > 0) {
							for (int i = 0; i < locationTypeList.getLength(); i++) {
								locationType = locationTypeList.item(i);
							}
						}

						Node viewPort = null, northeast = null, southwest = null, lat_northeast = null, lng_northeast = null, lat_southwest = null, lng_southwest = null;
						if (viewPortList.getLength() > 0) {
							for (int i = 0; i < viewPortList.getLength(); i++) {
								viewPort = viewPortList.item(i);

								if (viewPort.hasChildNodes()) {
									northeast = viewPort.getChildNodes()
											.item(1);
									southwest = viewPort.getChildNodes()
											.item(3);
								}

								/* Extract data from viewport field*/
								if (northeast.hasChildNodes()) {
									lat_northeast = northeast.getChildNodes()
											.item(1);
									lng_northeast = northeast.getChildNodes()
											.item(3);
								}

								if (southwest.hasChildNodes()) {
									lat_southwest = southwest.getChildNodes()
											.item(1);
									lng_southwest = southwest.getChildNodes()
											.item(3);
								}

							}
						}

						// Now, need to call CWRModelWriter to make data base
						// changes
						double[] coordValues = new double[2];
						coordValues[0] = Double.parseDouble(lat
								.getTextContent());
						coordValues[1] = Double.parseDouble(lng
								.getTextContent());

						double[] coordValuesNortheast = new double[2];
						coordValuesNortheast[0] = Double
								.parseDouble(lat_northeast.getTextContent());
						coordValuesNortheast[1] = Double
								.parseDouble(lng_northeast.getTextContent());

						double[] coordValuesSouthwest = new double[2];
						coordValuesSouthwest[0] = Double
								.parseDouble(lat_southwest.getTextContent());
						coordValuesSouthwest[1] = Double
								.parseDouble(lng_southwest.getTextContent());

						double distance = GeocodeUtilities.getDistance(coordValuesNortheast,
								coordValuesSouthwest); // Distance - km  between Northeast and Southeast

						if (distance <= THRESHOLD) { // Condition
							if (user_dec.equals(OPTION_DATABASE)) {
								if (mw.writeCoordValues(coordValues,
										locationType.getTextContent(),
										distance, ids.get(k))) { // Write into bd
									System.out.println(k
											+ " Update Success: Id record -> "
											+ ids.get(k));
								} else {
									System.out.println(k
											+ " Update Error: Id record -> "
											+ ids.get(k));
								}
							} else if (user_dec.equals(OPTION_FILE)) {
								if (mw.writeCoordValuesInFile(coordValues,
										locationType.getTextContent(),
										distance, ids.get(k), filename)) {
									System.out.println(k
											+ " Update Success: Id record -> "
											+ ids.get(k));
								} else {
									System.out.println(k
											+ " Update Error: Id record -> "
											+ ids.get(k));
								}
							} else {
								System.out.println("Error: Invalid option");
							}
						}else{
							System.out.println("Warning: "+distance+ " is bigger than the threshold "+THRESHOLD);
							if (mw.changeGeorefFlagStatus(ids.get(k))) { // Flag it
								System.out.println(k
										+ " Warning - No values but remove to future query: Id record -> "
										+ ids.get(k));
							} else {
								System.out.println(k
										+ " Update Error: Id record -> "
										+ ids.get(k));
							}
						}
					}
				} else { // Try less location values
					queries.remove(k);
					queries.add((k - 1), GeocodeUtilities.lessLocationValues(queries.get(k)));
					String[] array = queries.get(k).split("+");

					if (array.length >= 2) { // Only if size >= 2
						k--; // Repeat again
					}else{
						if (mw.changeGeorefFlagStatus(ids.get(k))) { // No results, flag it
							System.out.println(k
									+ " Warning - No values but remove to future query: Id record -> "
									+ ids.get(k));
						} else {
							System.out.println(k
									+ " Update Error: Id record -> "
									+ ids.get(k));
						}
					}

				}
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public static void main(String[] args) {
		GeoGoogle geo = new GeoGoogle();
		
		// Exception
		if(args.length == 0){
			System.out.println("Error: You need provide a genus \n Example: java -jar -Xmx750m exec.jar Aegilops");
		}else if(args.length == 1){
			geo.init(args[0]);
		}else if(args.length == 2){
			if(Validator.isString(args[0])){
				if(args[1].toLowerCase().equals(OPTION_NO_PREMIUM)){
					System.out.println("Not implemented yet");
				}else{
					System.out.println("Error: Bad parameter, try with java -jar -Xmx750m exec.jar Aegilops -np");
				}
			}
		}else{
			System.out.println("Error: Wrong parameters");
		}
	}

}
