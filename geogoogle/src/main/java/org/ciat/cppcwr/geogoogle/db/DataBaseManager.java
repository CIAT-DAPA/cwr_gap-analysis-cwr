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
package org.ciat.cppcwr.geogoogle.db;

import java.sql.Connection;
import java.sql.ResultSet;

/**
 * 
 * @author Héctor Tobón (htobon)
 */
public interface DataBaseManager {
	/**
	 * Initialize database driver
	 * 
	 * @return false if the driver was not found
	 */
	public boolean registerDriver();
	
	/**
	 * open the connection to the database
	 * 
	 * @param user
	 * @param password
	 * @return A Connection object type
	 */
	public Connection openConnection(String user, String password);
	
	/**
	 * This method close the connection with the database and frees resources.
	 * 
	 * @param conexion
	 * @return true if all was ok, and false otherwhise.
	 */
	public boolean closeConnection(Connection conexion);
	
	/**
	 * This method make a change in the database. This method has to start with
	 * the word UPDATE or INSERT. If you want to make a query, u should use the
	 * method makeQuery.
	 * 
	 * @param updateQuery
	 *            where is the SQL code to make an insert or an update. NOT a
	 *            select.
	 * @param conexion
	 *            . The object Connection.
	 * @return The number of rows that changed, or -1 in case an error occurs.
	 */
	public int makeChange(String updateQuery, Connection conexion);
	
	/**
	 * This method execute a query. The query string should start with the word
	 * SELECT.
	 * 
	 * @param query
	 *            where is the SQL code to take data from the database.
	 * @param conexion
	 *            . The object Connection.
	 * @return ResulSet object that correspond to the query result. Or null if
	 *         there was an error.
	 */
	public ResultSet makeQuery(String query, Connection conexion);

}
