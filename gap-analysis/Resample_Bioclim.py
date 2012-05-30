# Purpose: Resample climatic variables

import arcgisscripting
gp = arcgisscripting.create(9.3)

gp.workspace = "G:/ncastaneda/clim/bio_10m_esri"
out_space = "G:/ncastaneda/clim/bio_30m_esri"

# Get a list of grids in the workspace.
#
rasters = gp.ListRasters("", "GRID")

for raster in rasters:
    gp.Resample_management(raster, out_space + "/"+ raster, "30","BILINEAR")
    print raster



