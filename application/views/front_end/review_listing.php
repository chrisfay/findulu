<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<form class="uniForm" action="demo3.php" method="post">

	<fieldset class="inlineLabels">
		
		<legend>
			Contact form (<a href="demo3a.html" class="unlink">before</a>|<a href="demo3b.html" class="">after1</a>|<a href="demo3c.html" class="">after2</a>)
		</legend>


		<div class="ctrlHolder">
			<label for="name"><em>*</em> Your Name</label>
			<input name="name" id="name" value="John Doe" type="text" class="textInput" />
		</div>

		<div class="ctrlHolder">
			<label for="email"><em>*</em> Email address</label>
			<input name="email" id="email" value="email@example.com" type="text" class="textInput" />
		</div>

		<div class="ctrlHolder">
			<label for="message"><em>*</em> Your Message</label>
			<textarea name="message" id="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</textarea>
		</div>

		<div class="ctrlHolder">
		<p class="label"><em>*</em> Rate our service</p>
			<div class="multiField" id="starify">
				<label for="vote1" class="blockLabel"><input type="radio" name="vote" id="vote1" value="1" /> Poor</label>
				<label for="vote2" class="blockLabel"><input type="radio" name="vote" id="vote2" value="2" /> Fair</label>
				<label for="vote3" class="blockLabel"><input type="radio" name="vote" id="vote3" value="3" checked="checked" /> Average</label>
				<label for="vote4" class="blockLabel"><input type="radio" name="vote" id="vote4" value="4" /> Good</label>
				<label for="vote5" class="blockLabel"><input type="radio" name="vote" id="vote5" value="5" /> Excellent</label>
			</div>
		</div>

		<div class="buttonHolder">
			<button type="reset" class="resetButton">Reset</button>
			<button type="submit" class="primaryAction">Submit</button>
		</div>

	</fieldset>
	
</form>