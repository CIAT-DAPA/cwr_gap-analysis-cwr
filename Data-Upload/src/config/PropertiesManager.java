package config;

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

import java.io.FileInputStream;
import java.io.IOException;
import java.util.Properties;

/**
 * @author Louis Reymondin
 * @author Hector Tobon (htobon)
 */
public class PropertiesManager {

	private static final String CONFIG_FILE = "config.txt";

	private Properties properties;

	public PropertiesManager() {
		properties = new Properties();
		try {
			properties.load(new FileInputStream(CONFIG_FILE));
		} catch (IOException e) {
			System.out
					.println("The configuration file (config.txt) has not been configured yet.");
			System.out.println(e.getMessage());
			System.exit(-1);
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
