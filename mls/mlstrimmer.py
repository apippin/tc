#! /usr/bin/env python

import os
import sys
import csv
import zipfile
import tempfile
from optparse import OptionParser
from xml.dom.minidom import parse, parseString

def ParseFile(xmlNode, zipfile):
	
	filename = os.path.join(options.directory, xmlNode.getAttribute("Name"))
	if options.verbose: print "Trimming %s" % (filename)
	
	#create dictionary of header locations
	headers = xmlNode.getElementsByTagName("MLSField")
	allColumns = False
	headerMap = {}
	for header in headers:
		if header.firstChild.data == "*":
			allColumns = True
		else:
			headerMap[header.firstChild.data] = -1
	if options.verbose: print "xml file says to save: %s" % (headerMap)
	
	try:
		f = csv.reader(open(filename))
		firstLineRead = False
		
		tf = tempfile.NamedTemporaryFile()
		
		headersToSave = []
		for line in f:
			if not firstLineRead:
				s1 = set(item for item in line if item != '')
				s2 = set(headerMap.keys())
				if allColumns:
					s2 = s1
				
				s3 = s1.intersection(s2)
				
				headerMap.clear()
				for i in s3:
					headerMap[i] = line.index(i)
				headersToSave = headerMap.keys()
				if options.verbose: print "The columns that exist in both the xml and csv files are:" % (headersToSave)
				firstLineRead = True
			
			lineToWrite = ""
			for h in headersToSave:
				lineToWrite = "%s\"%s\"," % (lineToWrite, line[headerMap[h]])
			if options.verbose: print lineToWrite
			tf.write(lineToWrite + "\n")
			#if options.verbose: print line
		zipfile.write(tf.name, xmlNode.getAttribute("Name"))
		tf.close()
		
	except IOError, e:
		print e
		sys.exit()



if __name__ == '__main__':
	# read in command line arguments and parse them
	parser = OptionParser()
	parser.add_option("-c", dest="configFile", default="MLSRequiredFields.xml",
		help="xml config file name")
	parser.add_option("-d", "--directory", dest="directory", default=os.environ["PWD"],
		help="directory where csv files are located")
	parser.add_option("-v", "--verbose", dest="verbose", action="store_true",
		help="show verbose messaging")
	(options, args) = parser.parse_args()

	# make sure xml file exists and we can read it
	if not os.access(options.configFile, os.R_OK):
		print "%s is not accessible" % (options.configFile)
		sys.exit()
	# make sure we have write perms on the directory
	if not os.access(options.directory, os.W_OK):
		print "%s is not accessible" % (options.directory)
		sys.exit()
	
	# read in the xml
	dom1 = parse(options.configFile)
	mlsFiles = dom1.firstChild.childNodes[1].getElementsByTagName("MLSFile")
	
	# make sure all files are accessible (if they are not optional)
	for csvFile in mlsFiles:
		fullname = os.path.join(options.directory, csvFile.getAttribute("Name"))
		if not os.access(fullname, os.W_OK):
			print "%s does not exist, or has the wrong permissions" % (fullname)
			sys.exit()
		
	
	# create zip file
	try:
		zfile = zipfile.ZipFile(os.path.join(options.directory, "EQZipFile.zip"), "w")
		for csvFile in mlsFiles:
			ParseFile(csvFile, zfile)
		zfile.close()
	except IOError, e:
		print e
		sys.exit()
	
	# delete original files
	for csvFile in mlsFiles:
		fullname = os.path.join(options.directory, csvFile.getAttribute("Name"))
		try:
			os.remove(fullname)
		except OSError, e:
			print "could not delete %s, please delete it manually" % (fullname)
	

	if options.verbose: print "Finished!"
