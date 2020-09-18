#!/usr/bin/env python

from constraint import *
from random import randint
import mysql.connector
from mysql.connector import errorcode
import operator
import sys

config = {
    'user': 'root',
    'password': 'root',
    'host': 'localhost',
    'database': 'bee'
}

try:
    cnx = mysql.connector.connect(**config)
except mysql.connector.Error as err:
    if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
        print("Incorrect username and password.")
    elif err.errno == errorcode.ER_BAD_DB_ERROR:
        print("Database does not exist.")
    else:
        print("Database error. Exiting...")
        sys.exit()

cursor = cnx.cursor(buffered=True)
query = ("SELECT attribute_name FROM bee_quiz_attributes ORDER BY attribute_id ASC")
cursor.execute(query)
cursor.close()

# Store a reference to the ordered tuple of column names. This is used as a
# reference when adding column names to items in PROD.
attribute_names = []
for row in cursor:
    attribute_names.append(row[0])
attribute_names = tuple(attribute_names)

# INCOMPATIBILITY CONSTRAINTS restricting the set of possible requirements.
# Such constraints help to ensure that customer requirements remain
# consistent.
COMP = set()

# PRODUCT CONSTRAINTS responsible for restricting the possible instantiations
# of variables in P. Such constraints can be seen as compatibility constraints
# specifically used to enumerate the offered set of products.
PROD = {}
# Get all organisation IDs.
cursor = cnx.cursor(buffered=True)
query = ("SELECT organisation_id FROM bee_quiz_organisations")
cursor.execute(query)
cursor.close()
for row in cursor:
    org_id = row[0]
    # Get the attributes for this organisation.
    cursor = cnx.cursor(buffered=True)
    query = ("SELECT attribute_id FROM bee_quiz_attribute_relationships WHERE organisation_id = " + str(org_id))
    cursor.execute(query)
    cursor.close()
    # Populate our products with these attributes.
    attr = set()
    for row in cursor:
        attr.add(row[0])
    # We keep PROD as a dict.
    PROD.update({org_id : tuple(attr)})

# FILTER CONSTRAINTS define the relationship between customer requirements and
# products, e.g. 'students outside of a certain university should not receive
# recommendations from support organisations exclusive to that university.'
FILT = set()

# CUSTOMER REQUIREMENTS define what the customer (student) is looking for.
CR = set()
args = sys.argv[1].split(',')
for answer_id in args:
    # Get the attributes satisfied by the chosen quiz answers.
    cursor = cnx.cursor(buffered=True)
    query = ("SELECT attribute_id FROM bee_quiz_attribute_relationships WHERE answer_id = " + str(answer_id))
    cursor.execute(query)
    cursor.close()
    # Populate our customer requirements with these attributes,
    # ensuring no duplicates.
    for row in cursor:
        CR.add(row[0])
CR = tuple(CR)

# Compute the best match and return it to PHP.
results = {}
problem = Problem()
for org_id, prod in PROD.items():
    problem.reset()
    problem.addVariable("prod", prod)
    problem.addConstraint(SomeInSetConstraint(CR)) # Test to see if prod is in CR.
    results.update({org_id : len(problem.getSolutions())})
# Sort our results from most matched to least matched.
sorted_results = sorted(results.items(), key=operator.itemgetter(1), reverse=True)

# Close our SQL connection.
cnx.close()

# Return the best result to the calling PHP script.
print(sorted_results[0][0])