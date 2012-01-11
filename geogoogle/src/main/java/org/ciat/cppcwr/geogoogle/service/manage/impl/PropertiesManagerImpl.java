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
package org.ciat.cppcwr.geogoogle.service.manage.impl;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.Properties;

import org.ciat.cppcwr.geogoogle.service.manage.PropertiesManager;

import com.google.inject.Singleton;

/**
 * @author Louis Reymondin
 * @author Hector Tobon (htobon)
 */
@Singleton
public class PropertiesManagerImpl implements PropertiesManager {

	private static final String CONFIG_FILE = "settings.properties";

	private Properties properties;

	// private static PropertiesManagerImpl instance;

	// public static PropertiesManagerImpl getInstance() {
	// if (instance == null)
	// throw new RuntimeException("Instance has not been initialized");
	//
	// return instance;
	// }

	// public static void register(String propertiesPath) {
	// if (instance != null)
	// throw new RuntimeException("Instance has already been initialized");
	//
	// instance = new PropertiesManagerImpl(propertiesPath);
	// }

	public PropertiesManagerImpl() {
		properties = new Properties();
		try {
			properties.load(new FileInputStream(CONFIG_FILE));
		} catch (IOException e) {
			copyTemplateFile();
			System.out
					.println("The configuration file (settings.properties) has not been configured yet. A template has been copied to the root directory for you to edit it.");
			e.getLocalizedMessage();
			System.exit(-1);
		}
	}

	/**
	 * Copy template settings.properties file from resource path to project root
	 * directory.
	 */
	private static void copyTemplateFile() {
		try {
			InputStream in = new FileInputStream("src/main/resources/"
					+ CONFIG_FILE);
			OutputStream out = new FileOutputStream(CONFIG_FILE);
			byte[] buf = new byte[1024];
			int len;
			while ((len = in.read(buf)) > 0) {
				out.write(buf, 0, len);
			}
			out.flush();
			out.close();
			in.close();
		} catch (FileNotFoundException e) {
			System.out.println(e.getLocalizedMessage());
			System.exit(-1);
		} catch (IOException e) {
			System.out.println(e.getLocalizedMessage());
			e.printStackTrace();
		}
	}

	public boolean existProperty(String name) {
		return properties.get(name) != null;
	}

	public String getProperty(String name) {
		return properties.getProperty(name);
	}

	public int getPropertiesAsInt(String name) {
		return Integer.parseInt(getProperty(name));
	}

	public String[] getPropertiesAsStringArray(String name) {
		return getProperty(name).split(";");
	}

	public int[] getPropertiesAsIntArray(String name) {
		String[] str = getProperty(name).split(";");
		int[] array = new int[str.length];
		for (int i = 0; i < str.length; i++)
			array[i] = Integer.parseInt(str[i]);

		return array;
	}

	public float[] getPropertiesAsFloatArray(String name) {
		String[] str = getProperty(name).split(";");
		float[] array = new float[str.length];
		for (int i = 0; i < str.length; i++)
			array[i] = Float.parseFloat(str[i]);

		return array;
	}

	public float getPropertiesAsFloat(String name) {
		return Float.parseFloat(getProperty(name));
	}
}
