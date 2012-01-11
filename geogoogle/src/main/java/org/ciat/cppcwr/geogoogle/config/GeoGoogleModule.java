package org.ciat.cppcwr.geogoogle.config;

import org.ciat.cppcwr.geogoogle.service.manage.PropertiesManager;
import org.ciat.cppcwr.geogoogle.service.manage.impl.PropertiesManagerImpl;

import com.google.inject.AbstractModule;

public class GeoGoogleModule extends AbstractModule{

	@Override
	protected void configure() {
		// injects
		bind(PropertiesManager.class).to(PropertiesManagerImpl.class);
		
	}

}
