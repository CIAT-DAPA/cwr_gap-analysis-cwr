package dao;

import java.sql.Connection;
import java.sql.Driver;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;

import config.PropertiesManager;

public class MySQLDAOManager {

	private Connection connection;

	public MySQLDAOManager(PropertiesManager propManager)
			throws ClassNotFoundException, SQLException {
		Class.forName("com.mysql.jdbc.Driver");

		this.connection = DriverManager.getConnection(
				"jdbc:mysql://" + propManager.getProperty("mysql.host") + "/"
						+ propManager.getProperty("mysql.database"),
				propManager.getProperty("mysql.user"),
				propManager.getProperty("mysql.password"));
	}
	
	public ArrayList<String> getColumns() throws SQLException {
		ArrayList<String> columns = new ArrayList<>();
		// Preparar consulta
		Statement statement = connection.createStatement();
		ResultSet rs = statement.executeQuery ("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = 'raw_occurrences' ORDER BY ORDINAL_POSITION ASC");
		
		// recorriendo todo el resultado.
		while(rs.next()) {
			columns.add(rs.getString(1));			
		}
		
		return columns;
		
	}

}
