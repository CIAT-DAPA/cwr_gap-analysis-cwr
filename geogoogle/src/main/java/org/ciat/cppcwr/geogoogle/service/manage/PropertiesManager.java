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
package org.ciat.cppcwr.geogoogle.service.manage;

/**
 * @author Héctor Tobón (htobon)
 *
 */
public interface PropertiesManager {
	
	public boolean existProperty(String name);
	
	public String getProperty(String name);

	public int getPropertiesAsInt(String name);

	public String[] getPropertiesAsStringArray(String name);

	public int[] getPropertiesAsIntArray(String name);

	public float[] getPropertiesAsFloatArray(String name);

	public float getPropertiesAsFloat(String name);

}
