package org.ciat.cppcwr.geogoogle.utils;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.Properties;

/** 
 * @author Louis Reymondin
 */
public class PropertiesManager {

	private Properties properties;

	private static PropertiesManager instance;

	public static PropertiesManager getInstance() {
		if (instance == null)
			throw new RuntimeException("Instance has not been initialized");

		return instance;
	}

	public static void register(String propertiesPath) {
		if (instance != null)
			throw new RuntimeException("Instance has already been initialized");

		instance = new PropertiesManager(propertiesPath);
	}

	private PropertiesManager(String propertiesPath) {
		properties = new Properties();
		try {
			properties.load(new FileInputStream(propertiesPath));
		} catch (IOException e) {
			copyTemplateFile();			
			System.out.println("The configuration file (settings.properties) has not been configured yet. A template has been copied to the root directory for you to edit it.");
			e.getLocalizedMessage();
			System.exit(-1);
		}
	}

	/**
	 * Copy template settings.properties file from resource path to project root directory.
	 */
	private static void copyTemplateFile() {
		try {
			InputStream in = new FileInputStream("src/main/resources/settings.properties");
			OutputStream out = new FileOutputStream("settings.properties");
			byte[] buf = new byte[1024];
			int len;
			while((len = in.read(buf))>0) {
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
	
	public static void main(String[] args) {
		copyTemplateFile();
	}

	public boolean existProperty(String name) {
		return properties.get(name) != null;
	}

	public String getPropertiesAsString(String name) {
		return properties.getProperty(name);
	}

	public int getPropertiesAsInt(String name) {
		return Integer.parseInt(getPropertiesAsString(name));
	}

	public String[] getPropertiesAsStringArray(String name) {
		return getPropertiesAsString(name).split(";");
	}

	public int[] getPropertiesAsIntArray(String name) {
		String[] str = getPropertiesAsString(name).split(";");
		int[] array = new int[str.length];
		for (int i = 0; i < str.length; i++)
			array[i] = Integer.parseInt(str[i]);

		return array;
	}

	public float[] getPropertiesAsFloatArray(String name) {
		String[] str = getPropertiesAsString(name).split(";");
		float[] array = new float[str.length];
		for (int i = 0; i < str.length; i++)
			array[i] = Float.parseFloat(str[i]);

		return array;
	}

	public float getPropertiesAsFloat(String name) {
		return Float.parseFloat(getPropertiesAsString(name));
	}
}
