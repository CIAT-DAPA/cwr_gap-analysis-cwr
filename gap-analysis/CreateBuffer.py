# Purpose: Create CA50 Buffer

import arcgisscripting
import os, sys
gp = arcgisscripting.create(9.3)

gp.overwriteoutput = True

from time import clock
t0 = clock()

gp.toolbox = "analysis"
gp.toolbox = "management"

#gp.workspace = "G:/ncastaneda/gap-analysis-tomato/gap_tomato/occurrences/test"
#gp.workspace = sys.argv[1]
gp.workspace = gp.GetParameterAsText(0)

##out_workspace = "d:/MyData/Results"
##clip_features = "d:/MyData/TestArea/Boundary.shp"
#
coordsys = "Coordinate Systems/Geographic Coordinate Systems/World/WGS 1984.prj"

# Get a list of feature classes in the workspace.
#
fcs = gp.ListFeatureClasses("test3*")

try:
    for fc in fcs:
        # Defining shapefile projection
        gp.defineprojection(fc, coordsys)
        # Creating CA50 buffer
        gp.buffer_analysis(fc, "test" + fc, "50 kilometers", "#", "#", "ALL", "Taxon")
        # Process: PolygonToRaster
        #gp.PolygonToRaster_conversion("test" + fc, "FID", fc[:2])
        print "listo el pollo"
#
except:
    # Print error message if an error occurs
    gp.GetMessages()

# Tiempo de procesamiento
for i in xrange(10000000):
    pass
print "\n\n%.2f sec" % (clock() - t0)
    