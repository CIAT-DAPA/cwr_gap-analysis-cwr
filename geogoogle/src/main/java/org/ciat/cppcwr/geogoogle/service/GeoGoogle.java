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
import org.ciat.cppcwr.geogoogle.dataconnector.reader.DataModelReader;
import org.ciat.cppcwr.geogoogle.dataconnector.writer.DataModelWriter;
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
	private final String LT_ROOFTOP = "ROOFTOP";
	private final String LT_RANGE_INTERPOLATED = "RANGE_INTERPOLATED";
	private final String LT_GEOMETRIC_CENTER = "GEOMETRIC_CENTER";
	private final String LT_APPROXIMATE = "APPROXIMATE";
	private final String OPTION_DATABASE = "1";
	private final String OPTION_FILE = "2";
	private Console console = System.console();

	/*
	 * Update data at bd
	 */
	public void init() {

		Injector inject = Guice.createInjector(new GeoGoogleModule());
		UrlSignerGenerator usg = inject.getInstance(UrlSignerGenerator.class);
		mr = inject.getInstance(DataModelReader.class);
		mw = inject.getInstance(DataModelWriter.class);

		ArrayList<String[]> data = mr.getDBData();
		ArrayList<String> queries = transformToValidQuery(data);
		ArrayList<String> ids = returnIds(data);
		String user_dec = console
				.readLine("       OPTIONS \n [1] Save into database \n [2] Save into a file \n Select your response: ");

		try {
			for (int k = 0; k < queries.size(); k++) {
				URL url = new URL(URL_SEND + queries.get(k));
				System.out.println(url.getPath() + url.getQuery());
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

						double distance = getDistance(coordValuesNortheast,
								coordValuesSouthwest);

						if (user_dec.equals(OPTION_DATABASE)) {

						} else if (user_dec.equals(OPTION_FILE)) {
							String filename = console
									.readLine("Enter a filename (with a pat if you wish, default c:/)");
							if (mw.writeCoordValuesInFile(coordValues,
									locationType.getTextContent(), distance,
									ids.get(k), filename)) {
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
					}
				}
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/* Remove any strange value from the query */
	public ArrayList<String> transformToValidQuery(
			ArrayList<String[]> dataLocationList) {
		ArrayList<String> queries = new ArrayList<String>();

		for (int i = 0; i < dataLocationList.size(); i++) {
			queries.add("address="
					+ URLEncoder.encode(dataLocationList.get(i)[1]) + "%");
		}

		return queries;
	}

	/* Get Ids from location List */
	public ArrayList<String> returnIds(ArrayList<String[]> dataLocationList) {
		ArrayList<String> ids = new ArrayList<String>();

		for (int i = 0; i < dataLocationList.size(); i++) {
			ids.add(dataLocationList.get(i)[0]);
		}

		return ids;
	}

	// Get the distance to 2 coordinates (km)
	public double getDistance(double[] coord1, double[] coord2) {
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

	public static void main(String[] args) {
		GeoGoogle geo = new GeoGoogle();
		geo.init();
	}

}
