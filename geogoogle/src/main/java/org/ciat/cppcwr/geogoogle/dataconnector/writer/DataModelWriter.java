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
package org.ciat.cppcwr.geogoogle.dataconnector.writer;

import java.util.ArrayList;

/**
 * @author Héctor Tobón (htobon)
 *
 */
public interface DataModelWriter {
	public boolean writeCoordValues(ArrayList<double[]> coordList, ArrayList<String> locationTypeList, double distance, ArrayList<int[]> idOccurrencesList);
	public boolean writeCoordValues(double[] coord, String locationType, double distance, String idOccurrence);
	public boolean writeCoordValuesInFile(double[] coord, String locationType, double distance,String idOccurrence);
}
