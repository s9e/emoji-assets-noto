<?php

$dir = $_SERVER['argv'][1];
array_map('patchSVG', glob($dir . '/*.svg'));

function patchSVG($filepath)
{
	$dom = new DOMDocument;
	$dom->load($filepath);
	$svg = $dom->documentElement;

	$attributes = [
		'viewBox'           => '((?<x>-?[.\\d]+)[ ,](?<y>-?[.\\d]+) (?<w>[.\\d]+)[ ,](?<h>[.\\d]+))',
		'enable-background' => '(new (?<x>-?[.\\d]+) (?<y>-?[.\\d]+) (?<w>[.\\d]+) (?<h>[.\\d]+))',
		'width'             => '((?<w>[.\\d]+))',
		'height'            => '((?<h>[.\\d]+))'
	];

	$values = [];
	foreach ($attributes as $attrName => $regexp)
	{
		if (!$svg->hasAttribute($attrName))
		{
			continue;
		}
		if (!preg_match($regexp, $svg->getAttribute($attrName), $m))
		{
			echo "Cannot parse $attrName in $filepath\n";

			continue;
		}
		$values += $m;
	}

	if (!isset($values['w'], $values['h']))
	{
		echo "Cannot parse dimensions in $filepath\n";

		return;
	}

	$x      = (isset($values['x'])) ? $values['x'] : 0;
	$y      = (isset($values['y'])) ? $values['y'] : 0;
	$width  = $values['w'];
	$height = $values['h'];
	$radius = round($width * .1, 1);

	$defs     = $svg->appendChild($dom->createElement('defs'));
	$clipPath = $defs->appendChild($dom->createElement('clipPath'));
	$clipPath->setAttribute('id', 'clip-rounded-rectangle');
	$rect     = $clipPath->appendChild($dom->createElement('rect'));
	$rect->setAttribute('x',      $x);
	$rect->setAttribute('y',      $y);
	$rect->setAttribute('width',  $width);
	$rect->setAttribute('height', $height);
	$rect->setAttribute('rx',     $radius);
	$rect->setAttribute('ry',     $radius);

	$g = $dom->createElement('g');
	$g->setAttribute('clip-path', 'url(#clip-rounded-rectangle)');

	$i = $svg->childNodes->length;
	while (--$i >= 0)
	{
		$node     = $svg->childNodes[$i];
		$nodeName = $node->nodeName;
		switch ($nodeName)
		{
			case '#comment':
			case 'clipPath':
			case 'comment':
			case 'defs':
			case 'desc':
			case 'i:pgf':
			case 'linearGradient':
			case 'metadata':
			case 'sodipodi:namedview':
			case 'style':
			case 'title':
				// Do nothing
				break;

			case '#text':
			case 'circle':
			case 'ellipse':
			case 'g':
			case 'line':
			case 'path':
			case 'polygon':
			case 'polyline':
			case 'rect':
			case 'switch':
			case 'text':
			case 'use':
				$g->insertBefore($node, $g->firstChild);
				break;

			default:
				echo "Ignored $nodeName element in $filepath\n";
		}

		$svg->appendChild($g);
	}

	$rect     = $g->insertBefore($dom->createElement('rect'), $g->firstChild);
//	$rect     = $g->appendChild($dom->createElement('rect'));
	$rect->setAttribute('x',      $x);
	$rect->setAttribute('y',      $y);
	$rect->setAttribute('width',  $width);
	$rect->setAttribute('height', $height);
	$rect->setAttribute('rx',     $radius);
	$rect->setAttribute('ry',     $radius);
	$rect->setAttribute('fill',   'none');
	$rect->setAttribute('stroke', 'black');
	$rect->setAttribute('stroke-width', '2%');
	$rect->setAttribute('stroke-opacity', '.1');

	if ($height < $width)
	{
		$y -= ($width - $height) / 2;
		$height = $width;
	}
	elseif ($height > $width)
	{
		$x -= ($height - $width) / 2;
		$width = $height;
	}
	$svg->removeAttribute('width');
	$svg->removeAttribute('height');
	$svg->setAttribute('viewBox', "$x $y $width $height");

	$dom->save($filepath);
}