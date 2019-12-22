<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

</body>
<script type="text/javascript">
	// Changes XML to JSON
	function xmlToJson(xml) {
		
		// Create the return object
		var obj = {};

		if (xml.nodeType == 1) { // element
			// do attributes
			if (xml.attributes.length > 0) {
			obj["@attributes"] = {};
				for (var j = 0; j < xml.attributes.length; j++) {
					var attribute = xml.attributes.item(j);
					obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
				}
			}
		} else if (xml.nodeType == 3) { // text
			obj = xml.nodeValue;
		}

		// do children
		if (xml.hasChildNodes()) {
			for(var i = 0; i < xml.childNodes.length; i++) {
				var item = xml.childNodes.item(i);
				var nodeName = item.nodeName;
				if (typeof(obj[nodeName]) == "undefined") {
					obj[nodeName] = xmlToJson(item);
				} else {
					if (typeof(obj[nodeName].push) == "undefined") {
						var old = obj[nodeName];
						obj[nodeName] = [];
						obj[nodeName].push(old);
					}
					obj[nodeName].push(xmlToJson(item));
				}
			}
		}
		return obj;
	};


	var xml = 	'<ALEXA VER="0.9" URL="davidwalsh.name/" HOME="0" AID="=">'+
					'<SD TITLE="A" FLAGS="" HOST="davidwalsh.name">'+
						'<TITLE TEXT="David Walsh Blog :: PHP, MySQL, CSS, Javascript, MooTools, and Everything Else"/>'+
						'<LINKSIN NUM="1102"/>'+
						'<SPEED TEXT="1421" PCT="51"/>'+
					'</SD>'+
					'<SD>'+
						'<POPULARITY URL="davidwalsh.name/" TEXT="7131"/>'+
						'<REACH RANK="5952"/>'+
						'<RANK DELTA="-1648"/>'+
					'</SD>'+
				'</ALEXA>';
	var parser = new DOMParser();
	var xml = parser.parseFromString(xml, "text/xml");
	var json = JSON.stringify(xmlToJson(xml));
	console.log(json)

</script>
</html>