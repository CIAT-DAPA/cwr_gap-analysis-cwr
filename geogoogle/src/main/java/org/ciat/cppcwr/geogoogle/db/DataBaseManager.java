/**
 * Copyright 2012 International Center for Tropical Agriculture (CIAT).
 * This file is part of GeoGoogle (GAP ANALYSIS CWR):
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
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 */
package org.ciat.cppcwr.geogoogle.db;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class DataBaseManager {

	/**
	 * Initialize database driver
	 * 
	 * @return false if the driver was not found
	 */
	public static boolean registerDriver() {
		try {
			Class.forName("org.gjt.mm.mysql.Driver").newInstance();
		} catch (ClassNotFoundException e) {
			e.printStackTrace();
			return false;
		} catch (InstantiationException e) {

			e.printStackTrace();
		} catch (IllegalAccessException e) {

			e.printStackTrace();
		}
		return true;
	}

	/**
	 * open the connection to the database
	 * 
	 * @param user
	 * @param password
	 * @return A Connection object type
	 */
	public static Connection openConnection(String user, String password) {
		try {
			Connection conexion = DriverManager.getConnection(
					"jdbc:mysql://IP:PORT/DATABASE_NAME", user, password);
			return conexion;
		} catch (SQLException e) {
			e.printStackTrace();
			return null;
		}
	}

	/**
	 * This method close the conection with the database and frees resources.
	 * 
	 * @param conexion
	 * @return true if all was ok, and false otherwhise.
	 */
	public static boolean closeConnection(Connection conexion) {
		try {
			conexion.close();
		} catch (SQLException e) {
			e.printStackTrace();
			return false;
		}
		return true;
	}

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
	 * @return The number of rows that changed, or -1 in case an error ocurrs.
	 */
	public static int makeChange(String updateQuery, Connection conexion) {
		Statement stmMakeChange;
		try {
			if (updateQuery.toLowerCase().startsWith("update")
					|| updateQuery.toLowerCase().startsWith("insert")) {
				
				stmMakeChange = conexion.createStatement();
				int v = stmMakeChange.executeUpdate(updateQuery);
				stmMakeChange.close();
				return v;
			}
		} catch (Exception e) {
			System.out.println("QUERY ERROR: " + updateQuery);
			System.out.println(e.getMessage());
			return -1;
		}
		return -1;
	}

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
	public static ResultSet makeQuery(String query, Connection conexion) {		
		try {
			if (query.toLowerCase().startsWith("select")) {
				return conexion.createStatement().executeQuery(query);
			}
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return null;
	}

	public static String correctStringToQuery(String cadena) {
		return cadena.replaceAll("'", "\\\\'");
	}
}
