<?php
require_once 'lib/log_client.php';
?>
<html>

<head>
<meta http-equiv="Expires" Content="0" />
<link rel="StyleSheet" href="style.css" type="text/css" media="screen" />
<title>Carlos Torchia's Developer/Student Portfolio</title>
</head>

<body>

<h1>Carlos Torchia's Developer/Student Portfolio</h1>

<img height="200" src="carlos-torchia.png" />
<p>
Hi, I have been a student at University of Victoria studying computer science. This page contains
various applications and papers that I wrote while studying here, as well as some software I've been
developing after my graduation. Feel free to try them and look at their source.
</p>
<p>
<i>
Carlos Torchia<br />
<a href="http://www.linkedin.com/in/cetorchia">linkedin.com/in/cetorchia</a> <br />
<?php
echo date('Y-m-d', getlastmod());
?>
</i>
</p>


<h2>Programs</h2>

<p>
<table>
<tr>
<th>Application</th>
<th>Language</th>
<th>Source code</th>
<th>Date</th>
</tr>
<tr>
<td>seepackets: A packet sniffer</td>
<td>C</td>
<td><a href="https://github.com/cetorchia/src/blob/master/seepackets.c">seepackets.c</a></td>
<td>Jan 2009</td>
</tr>
<tr>
<td><a href="http://web.uvic.ca/~ctorchia/chat/">Chat Room</a></td>
<td>PHP</td>
<td><a href="https://github.com/cetorchia/chat">chat</a></td>
<td>May 2010 - Aug 2012</td>
</tr>
<tr>
<td><a href="rnass.php">RNASS: An RNA secondary structure generator</a> (beta)</td>
<td>PHP</td>
<td><a href="https://github.com/cetorchia/portfolio/blob/master/rnass.php">rnass.php</a></td>
<td>Mar 2011 </td>
</tr>
<tr>
<td><a href="knapsack.php">PTAS 0/1 Knapsack solver</a></td>
<td>PHP</td>
<td><a href="https://github.com/cetorchia/portfolio/blob/master/knapsack.php">knapsack.php</a></td>
<td>Mar 2011</td>
</tr>
<tr>
<td><a href="http://web.uvic.ca/~ctorchia/thinklog/">Thinklog: thought analysis tool</a></td>
<td>PHP, MySQL</td>
<td>
<a href="https://github.com/cetorchia/thinklog">thinklog</a>
</td>
<td>ongoing</td>
</tr>
<tr>
<td><a href="http://web.uvic.ca/~ctorchia/nutrition/">Nutritional information</a></td>
<td>PHP, MySQL</td>
<td><a href="https://github.com/cetorchia/nutrition">nutrition</a></td>
<td>Dec 2010</td>
</tr>
<tr>
<td>PyCmdBot: Google Talk bot that takes commands from chat users</td>
<td>Python, xmpp</td>
<td><a href="https://github.com/cetorchia/pycmdbot">pycmdbot</a></td>
<td>Apr 2012</td>
</tr>
<tr>
<td>Utility scripts for making backups, parsing files</td>
<td>Perl, Bash</td>
<td><a href="https://github.com/cetorchia/bin">bin</a></td>
<td>ongoing</td>
</tr>
<tr>
<td>Utilities and libraries for parsing files, computing egcd etc.</td>
<td>Perl, Octave, C</td>
<td><a href="https://github.com/cetorchia/src">src</a></td>
<td>ongoing</td>
</tr>
<tr>
<td><a href="https://analytics.terapeak.com/">PayPal Analytics</a> (with AERS)</td>
<td>(confidential)</td>
<td>(confidential)</td>
<td>Jul 2010</td>
</tr>
<tr>
<td><a href="http://listinganalytics.com/">Listing Analytics</a> (with AERS)</td>
<td>(confidential)</td>
<td>(confidential)</td>
<td>Aug 2010</td>
</tr>
</table>
</p>

<h2>Papers</h2>

<p>
<table>
<tr>
<th>Paper</th>
<th>Date</th>
</tr>
<tr>
<td><a href="papers/fuzzy-navigation.pdf">Fuzzy logic in mobile robot navigation</a></td>
<td>Oct 2008</td>
</tr>
<tr>
<td><a href="papers/arrangements.pdf">Line arrangements</a></td>
<td>Dec 2010</td>
</tr>
<td><a href="papers/flexible-info.pdf">Flexible information retrieval: harvesting the ramblings of an idle blogger</a></td>
<td>Apr 2011</td>
</tr>
<tr>
<td><a href="papers/automatic-seq.pdf">Automatic sequences</a></td>
<td>July 2012</td>
</tr>
</table>
</p>

<h2>Interests</h2>

<p>
<ul>
<li>Web development, database programming</li>
<li>User experience, usability, self-documentation
  <ul>
    <li>How can user interfaces be designed so that they can be
        used to get more accurate information from people?</li>
    <li>How can user interfaces be designed so that users do not
        need to read a manual or spend a significant amount of time
        to learn how to use it?</li>
  </ul>
</li>
<li>Artificial intelligence, knowledge representation, data mining
  <ul>
    <li>Can a computer actually understand a concept?</li>
    <li>How can human thought be represented in a computer?</li>
    <li>How can a machine make meaningful inferences on human narrative?</li>
    <li>What useful information can be mined from social networks?</li>
    <li>How should web apps and databases be designed so that meaningful
        information can be efficiently mined?</li>
    <li>How can you design a search engine that will retrieve information
        that is relevant to a query?</li>
  </ul>
</li>
<li>Math proofs</li>
<li>Algorithms</li>
<li>Politics, sociology, economics
  <ul>
    <li>Can computing technology be 100% democratic? Can anything?</li>
    <li>What is democratic? Do we only think we have democracy?</li>
    <li>How can we make technology free people instead of make them
        into work-addicted cyborgs?</li>
    <li>How can data mined from medical and commercial databases add value
        to people's lives?</li>
    <li>How can education be reformed/budgeted so that everyone has the
        opportunity to be well-informed, but they still have the incentive
        to contribute enough to society.</li>
    <li>How can you satisfy your own needs while not harming others, including
        other life forms and future generations?</li>
  </ul>
</li>
<li>Communication, social interaction
  <ul>
    <li>How do you write or speak in a way such that people will
        have the most ease in understanding what you say?</li>
    <li>How do you teach mathematics so that anyone can understand it?</li>
    <li>How do you make people like you, but only for the right reasons?</li>
  </ul>
</li>
<li>Psychology, consciousness
  <ul>
    <li>Why am I here and you are there?</li>
    <li>Can a computer, a plant, or a waterfall be conscious in some way?</li>
    <li>What is the relationship between consciousness and intelligence?</li>
    <li>What does it mean to "understand" something?</li>
    <li>Does greater "mindfulness" lead to greater happiness? Why?</li>
  </ul>
</li>
</ul>
<hr />

<p><a href="http://www.csc.uvic.ca/">UVic CSC Home</a></p>

</body>

</html>

