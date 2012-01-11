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
package org.ciat.cppcwr.geogoogle.db.impl;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import org.ciat.cppcwr.geogoogle.db.DataBaseManager;
import org.ciat.cppcwr.geogoogle.service.manage.PropertiesManager;

import com.google.inject.Inject;

/**
 * @author Héctor Tobón (htobon)
 */
public class MySQLDataBaseManagerImpl implements DataBaseManager {

	@Inject
	private PropertiesManager pm;

	public boolean registerDriver() {
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

	public Connection openConnection() {
		try {
			Connection conexion = DriverManager.getConnection(
					"jdbc:mysql://" + pm.getProperty("mysql.server") + ":"
							+ pm.getProperty("mysql.port") + "/"
							+ pm.getProperty("mysql.database"),
					pm.getProperty("mysql.user"),
					pm.getProperty("mysql.password"));
			return conexion;
		} catch (SQLException e) {
			e.printStackTrace();
			return null;
		}
	}

	public boolean closeConnection(Connection conexion) {
		try {
			conexion.close();
		} catch (SQLException e) {
			e.printStackTrace();
			return false;
		}
		return true;
	}

	public int makeChange(String updateQuery, Connection conexion) {
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

	public ResultSet makeQuery(String query, Connection conexion) {
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
