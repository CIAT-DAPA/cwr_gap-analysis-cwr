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
package org.ciat.cppcwr.geogoogle.config;


import org.ciat.cppcwr.geogoogle.db.DataBaseManager;
import org.ciat.cppcwr.geogoogle.db.impl.MySQLDataBaseManager;
import org.ciat.cppcwr.geogoogle.dataconnector.reader.DataModelReader;
import org.ciat.cppcwr.geogoogle.dataconnector.reader.impl.CWRModelReaderImpl;
import org.ciat.cppcwr.geogoogle.dataconnector.writer.DataModelWriter;
import org.ciat.cppcwr.geogoogle.dataconnector.writer.impl.CWRModelWriterImpl;
import org.ciat.cppcwr.geogoogle.service.manage.PropertiesManager;
import org.ciat.cppcwr.geogoogle.service.manage.impl.PropertiesManagerImpl;

import com.google.inject.AbstractModule;
import com.google.inject.name.Names;

public class GeoGoogleModule extends AbstractModule {

	@Override
	protected void configure() {
		/* ---------- INJECTS CONFIGURATION ---------- */
		
		bind(PropertiesManager.class).to(PropertiesManagerImpl.class);
		bind(DataModelReader.class).to(CWRModelReaderImpl.class);
		bind(DataModelWriter.class).to(CWRModelWriterImpl.class);

		// Instancebinding for a DataBaseManager instance @Named as "MySQL".
		bind(DataBaseManager.class).annotatedWith(Names.named("MySQL")).to(
				MySQLDataBaseManager.class);
	}

}
