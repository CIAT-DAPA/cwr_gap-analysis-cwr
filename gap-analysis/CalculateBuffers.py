# Date: 31-March-2012
# Purpose: Create buffer areas (CA50)

import arcpy
from arcpy import env
import os

env.workspace = "C:\\Users\\ncp148\\Documents\\CPP_CWR\\_collaboration\\_fontagro\\gap_tomato\\occurrences"
out_feature = "C:\\Users\\ncp148\\Documents\\CPP_CWR\\_collaboration\\_fontagro\\gap_tomato"

##env.workspace = Parametro(0)
##out_feature = Parametro(1)

# Calculate CA50 for all records
#
try:
##    arcpy.Buffer_analysis("tomato_g.shp", out_feature, "1 Kilometer", "DISSOLVE", "ALL", "G")
    fcList = arcpy.ListFeatureClasses("*h.shp")
    for fc in fcList:
        arcpy.Buffer_analysis(fc, "test", "50 Kilometers", "ALL")
        print fc

##        # Create Buffer
##        arcpy.Buffer_analysis(file, out_feature + file, "50 Kilometers", "ALL")        
##        arcpy.Buffer_analysis(file, out_feature + "\\" + file, "50", "ALL")
    ##    print out_feature + "\\" + file
        # Shapefile to raster
    ##    arcpy.PolygonToRaster_conversion(out_feature + "\\" + file, )

##
### Set local variables
##inFeatures = "ca_counties.shp"
##valField = "NAME"
##outRaster = "c:/output/ca_counties"
##assignmentType = "MAXIMUM_AREA"
##priorityField = "MALES"
##cellSize = 0.5
##
### Execute PolygonToRaster
##arcpy.PolygonToRaster_conversion(inFeatures, valField, outRaster, assignmentType,
##                                 priorityField, cellSize)
##
##
##### Calculate CA50 for germplasm
####for file in arcpy.ListFiles("*g.shp"):
####    # Use splitext to set the output table name
####    #
####    print file
except:
    # If an error occurred while running a tool, then print the messages
    print arcpy.GetMessages()
