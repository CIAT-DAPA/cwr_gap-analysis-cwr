package org.ciat.cppcwr.geogoogle.config;

import org.ciat.cppcwr.geogoogle.db.DataBaseManager;
import org.ciat.cppcwr.geogoogle.db.impl.MySQLDataBaseManagerImpl;
import org.ciat.cppcwr.geogoogle.service.manage.PropertiesManager;
import org.ciat.cppcwr.geogoogle.service.manage.impl.PropertiesManagerImpl;

import com.google.inject.AbstractModule;
import com.google.inject.name.Names;

public class GeoGoogleModule extends AbstractModule {

	@Override
	protected void configure() {
		/* ---------- INJECTS CONFIGURATION ---------- */
		
		bind(PropertiesManager.class).to(PropertiesManagerImpl.class);

		// Instancebinding for a DataBaseManager instance @Named as "MySQL".
		bind(DataBaseManager.class).annotatedWith(Names.named("MySQL")).to(
				MySQLDataBaseManagerImpl.class);
	}

}
