# Extract climate to genepool area (Tomato)
# Python code for ArcGis10

# Name: ExtractByMask_Ex_02.py
# Description: Extracts the cells of a raster that correspond with the areas
#    defined by a mask.
# Requirements: Spatial Analyst Extension
# Author: ESRI

# Import system modules
import arcgisscripting
gp = arcgisscripting.create(9.3)
gp.toolbox = "SA"

# Set Snap raster
gp.SnapRaster = "G:\\ncastaneda\\gap-analysis-tomato\\gap_tomato_old\\maxent_modeling\\climate_data\\esri_grid\\bio_1"

# Set the extent environment using the Extent class.
#gp.Extent ="-182.0000, -61.0000, -28.0000, 54.0000"
gp.Extent ="-110.0000, -56.0000, -29.0000, 14.0000"

# Set environment settings
gp.workspace = "G:\\ncastaneda\\clim\\bio_2-5m_esri"

# Set local variables
##inRaster = "elevation"
inMaskData = "G:\\ncastaneda\\gap-analysis-tomato\\gap_tomato\\maxent_modeling\\climate_data\\genepool_area.shp"
OutFolder = "G:\\ncastaneda\\gap-analysis-tomato\\gap_tomato\\maxent_modeling\\climate_data\\esri_grid"

# Check out Spatial Analyst extension license
gp.CheckOutExtension("Spatial")

# Get a list of ESRI GRIDs from the workspace and print
# Get a list of grids in the workspace.
#
rasters = gp.ListRasters("*", "GRID")

for raster in rasters:
    # Process: ExtractByMask
    gp.ExtractByMask_sa(raster, inMaskData, OutFolder + "\\" + raster)
    # Data notification
    print "done " + raster
    