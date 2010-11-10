#!/usr/bin/env python
#
# Copyright (c) 2005-2008  Dustin Sallings <dustin@spy.net>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.
#
# <http://www.opensource.org/licenses/mit-license.php>
"""
Search script.
"""

import sys

import github

def usage():
    """display the usage and exit"""
    print "Usage:  %s keyword [keyword...]" % (sys.argv[0])
    sys.exit(1)

def mk_url(repo):
    return "http://github.com/%s/%s" % (repo.username, repo.name)

if __name__ == '__main__':
    g = github.GitHub()
    if len(sys.argv) < 2:
        usage()
    res = g.repos.search(' '.join(sys.argv[1:]))

    for repo in res:
        try:
            print "Found %s at %s" % (repo.name, mk_url(repo))
        except AttributeError:
            print "Bug: Couldn't format %s" % repo.__dict__