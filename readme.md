readme.md

# cakephp-bootstrap-suite
=======================

The CakePHP Bootstrap Suite (Bootstrap) is a plugin suite of CakePHP Helpers that attempt to make using the Bootstrap CSS and JS framework with CakePHP a lot easier.

It does this by providing helper classes that employ an Entity, Collection, and Wrapped Collection architecture to create and manipulate objects.

## A note about the Architecture
====
This plugin uses Entities to control and display everything. Each Entity is an intelligent class that holds all of its parts until it needs to spit everything out. 

Please note: the entity concept was devised quite separately from what is apparently coming in Cake3 for models, so any similarity is not intentional and does not imply a similar usage or implementation.

## How to use the PLugin
====
Make sure you install it following the instructions later in this readme.

There are a number of Helper Classes already provided. If you know what a Test Case is you  should check out the Test cases already provided to see examples of how each helper is used.

### Glyphicon example (easy)
===
Let's take a look at the pretty simple Glypicon helper
```php
	
	class ExamplesController extends AppController{
		public $uses = ['Bootstrap.BootstrapGlyphicon'];

		public function view(){}
	}

	//..view.ctp

	echo $this->BootstrapGlyphicon->create('minus');

	#will print out 
	<span class="glyphicon glyphicon-minus"></span>
```

That's it - that's how you use the GlyphiconHelper! Easy, eh?

Now let's look at a more complicated example, because this is where the setup will really start to shine.

```php
	
	class ExamplesController extends AppController{
		public $uses = ['Bootstrap.BootstrapTable'];

		public function view(){
			$this->set('examples', $this->Example->find('all'));
		}
	}

	#../view.ctp

	$t = $this->BootstrapTable->add();

	$t->Header->add()->addMultiple(['Name'],['Description']);
	foreach($examples as $example):
		$t->Body->add()->addMultiple(
				[$example['name']],
				[$example['description']],
			);
	endforeach;

	#print it out
	echo $t;

	#will give us 
	<table class='table table-hover table-striped table-notbordered table-notcondensed'>
		<thead>
			<tr>
				<th>Name</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Example One</td>
				<td>Description One</td>
			</tr>
			<tr>
				<td>Example Two</td>
				<td>Description Two</td>
			</tr>
			<tr>
				<td>Example Three</td>
				<td>Description Three</td>
			</tr>
		</tbody>
	</table>

```
Not bad! That's a pretty basic example. There are lots more things you can do, even with tables. But that should give you the gist of what's going on in each Helper.

Remember to take a look in the classes, the test cases provided, and any other examples (even the ones later in this doc) to figure out what's going on.


## Creating your own Helper (or understanding what's going on)
====

The whole system uses three main peices. An Entity (something like a list item), and either a plain Entity Collection (to hold the list items) or a Wrapped Entity Collection (to hold the list items and wrap them in a <ul> when it prints itself.) or any and all combinations of all three.

If you're looking for a good example of a completx setup, check out the BootstrapTableHelper - it has all three!

We take a look at each of those three and how/why/when to build your own.

### An example ENTITY
An entity is, and should be, a single idea - A paragraph is an entity, a cell in a table is an entity, an image is an entity, etc. 
An entity can also be made up of other entities. 
Some entities are special collections of other entities, we will look at those in the next section.

For our example we will make a completely useless Paragraph entity. A Paragraph entity, for example, would know that it uses the `<p>` tag, that it has a `class` of `foo` and that in between its tags is the content `Hello World!`. When anything attempts to get the string representation of it the Paragraph entity will convert itself into a full-formed paragraph that looks like this

```php
	
	#example class configuration
	class ParagraphEntity extends BootstrapHelperEntity{
		protected $_options = ['tag'=>'p'];
	}

	#example Paragraph creation with optional configuration
	$p = $this->ParagraphEntity->create('Hello World', ['htmlAttributes' => ['class' => 'foo']])


	#any of the following will produce...
	echo $p;
	echo $p->toString();
	echo (string) $p;

	#... this output
	<p class='foo'>Hello World!</p>

```

### Continuing the Example with an ENTITY COLLECTION

An EntityCollection is simply that, a collection of entities with no special relation to each other. Most of the Helpers in this plugin are Entity Collections; for example GlyphiconHelper extends BootstrapHelperEntityCollection and simply holds any glyphs you create in case you need to get to them later on.

There is a special case for Wrapped Entity Collections that we will look at in the next section.

Continuing on with our Paragraph entity, let's make the following adjustments. Let's make a paragraph collection that stores all the paragraphs we make.
```php

	#create a collection class that will use the ParagraphEntity class (from prev example) when it create()s new entities
	class ParagraphCollection extends BootstrapHelperEntityCollection{
		public $_entityClass = 'ParagraphEntity';
	}

	$this->ParagraphCollection->add('I');		# calling add() will instantiate an $_entityClass
	$this->ParagraphCollection->add('LOVE');	# call create(...params...) on the new instance
	$this->ParagraphCollection->add('ME');		# and set up some other collection-specific stuff
	$this->ParagraphCollection->addMultiple(	# you can also use addMultiple
		['LOTS'], 								# to call add multiple times
		['OF'], 								# by passing it an array of args for the add() function
		['PARAGRAPHS', ['htmlAttributes'=>['class'=>'serious']] ]
		);

	# we can do lots of other stuff with the collection, but let's just print it out for now
	echo $this->ParagraphCollection;

	# will produce the following
	<p>I</p>
	<p>LOVE</p>
	<p>ME</p>
	<p>LOTS</p>
	<p>OF</p>
	<p class='serious'>PARAGRAPHS</p>

```
### Continuing the example with a Wrapped Entity Collection

Next we'll take a look at the special case of a Wrapped Entity Collection. Oddly enough, this class was what actually drove me to implement the entity architecture of the plugin.

It is often the case that you have an arbitrary number of items (1 or more) that all need to go together AND need to be wrapped in something else. The previous design required that you call a begin() ... all your elements.. and an end().

```php
	
	#example of the old way
	echo $this->Nav->groupBegin();
	echo //...lots of nav elements...//
	echo $this->Nav->groupEnd();
```
This old was was very cumbersome and required you to not only keep track of where you were, but more importantly, required you to intimately know the details of how each Helper worked, what groups needed to be called when, and what items could (or had to) go in what groups. It was a pain in the butt.

Thus wrapped entity collections were actually the drivers for the whole re-write.

Let's take a look.

Let's change our example from before to make a little more sense. Instead of dealing with Paragraphs lets look at the ListGroupHelper. We'll use it to make a properly formatted Bootstrap ListGroup

```php

	#first, lets make a list item entity
	class ListEntity extends BootstrapHelperEntity{
		public $_options = [					# bootstrap list items are just
			'tag' => 'li',						# <li>'s
			'htmlAttributes' => [				# with a specific html class of
				'class' => 'list-group-item',   # 'list-group-item'
			]									# That's it! EASYMODE
		];										# The parent class will handle
	}											#   the rest
	
	#next, we'll make a wrapped entity collection
	class ListEntityCollection extends BootstrapHelperWrappedEntityCollection{
		public $_options = [			# The wrapped collection, unlike the
			'tag' => 'ul',				# regular collection, can actually take
			'htmlAttributes' => [		# formatting options.
				'class' => 'list-group'	# we'll see how they're used in a sec
			]
		]
	}

	#Let's get our ListGroup object
	#this call won't work if you copy/paste this example, you'll need
	#to set up $view and $settings first
	$listGroup = new ListEntityCollection($View, $settings);

	#Now let's add some list items (like we saw earlier)
	$listGroup->add('Cras justo odio');
	$listGroup->add('Dapibus ac facilisis in');
	$listGroup->addMultiple(
		['Morbi leo risus'],
		['Porta ac consectetur ac'],
		['Vestibulum at eros']
		);

	#now let's see why we bothered with a wrapped collectio
	echo $listGroup;

	#if we echo the collection we will get the following!
	<ul class="list-group">
	  <li class="list-group-item">Cras justo odio</li>
	  <li class="list-group-item">Dapibus ac facilisis in</li>
	  <li class="list-group-item">Morbi leo risus</li>
	  <li class="list-group-item">Porta ac consectetur ac</li>
	  <li class="list-group-item">Vestibulum at eros</li>
	</ul>

	# see how it wrapped all the list items for us? No need
	# for us to remember how they're supposed to go - it does that
	# all for us!
```
## Exceptions
====
There are a couple of classes that don't follow the Entity architecture yet.
NavHelper is a big one. In order to get some of them to work there are peices of the architecture that need to be designed first (like pass-through entities) and I haven't figured out the best way to do them yet. So, for now, some Helpers follow a slightly older, entity-less pattern. 

It's actually interesting to compare them to see how much work the newer design does automatically for you. Compare NavHelper and TableHelper - both do about the same amount of complexity/work, but one is vastly easier to read and maintain than the other.

## Installation
====

_[Using [Composer](http://getcomposer.org/)]_

Add the plugin to your project's `composer.json` - something like this:

	{
		"require": {
			"cwbit/cakephp-bootstrap-suite": "dev-master"
		}
	}

Because this plugin has the type `cakephp-plugin` set in it's own `composer.json` composer knows to install it inside your `/Plugins` directory (rather than in the usual 'Vendor' folder). It is recommended that you add `/Plugins/Bootstrap` to your cake app's .gitignore file. (Why? [read this](http://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).)

_[Manual]_

* Download and unzip the repo (see the download button somewhere on this git page)
* Copy the resulting folder into `app/Plugin`
* Rename the folder you just copied to `Bootstrap`

_[GIT Submodule]_

In your `app` directory type:

    git submodule add -b master git://github.com/cwbit/cakephp-bootstrap-suite.git Plugin/Bootstrap
    git submodule init
    git submodule update

_[GIT Clone]_

In your `app/Plugin` directory type:

    git clone -b master git://github.com/cwbit/cakephp-bootstrap-suite.git Bootstrap


### Enable plugin

In 2.0 you need to enable the plugin your `app/Config/bootstrap.php` file:
```php
    CakePlugin::load('Bootstrap');
```
If you are already using `CakePlugin::loadAll();`, then this is not necessary.