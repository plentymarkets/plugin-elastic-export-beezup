
# User Guide für das ElasticExportBeezUp Plugin

<div class="container-toc"></div>

## 1 Bei BeezUp registrieren

BeezUP ist ein Tool zur Verwaltung und Optimierung der Präsentation Ihrer Artikel in Preisportalen, Marktplätzen und bei Affiliate-Diensten. Weitere Informationen zu diesem Dienst finden Sie auf der Handbuchseite [BeezUp einrichten](https://www.plentymarkets.eu/handbuch/mandant-shop/standard/externe-dienste/beezup/). Um das Plugin für BeezUp einzurichten, registrieren Sie sich zunächst als Händler.

## 2 Das Format BeezUp-Plugin in plentymarkets einrichten

Um dieses Format nutzen zu können, benötigen Sie das Plugin Elastic Export.

Auf der Handbuchseite [Daten exportieren](https://www.plentymarkets.eu/handbuch/datenaustausch/daten-exportieren/#4) werden die einzelnen Formateinstellungen beschrieben.

In der folgenden Tabelle finden Sie Hinweise zu den Einstellungen, Formateinstellungen und empfohlenen Artikelfiltern für das Format **BeezUp-Plugin**.
<table>
    <tr>
        <th>
            Einstellung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
        <td class="th" colspan="2">
            Einstellungen
        </td>
    </tr>
    <tr>
        <td>
            Format
        </td>
        <td>
            <b>BeezUp-Plugin</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Bereitstellung
        </td>
        <td>
            <b>URL</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Dateiname
        </td>
        <td>
            Der Dateiname muss auf <b>.csv</b> oder <b>.txt</b> enden, damit Kauflux.de die Datei erfolgreich importieren kann.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Artikelfilter
        </td>
    </tr>
    <tr>
        <td>
            Aktiv
        </td>
        <td>
            <b>Aktiv</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Märkte
        </td>
        <td>
            Eine oder mehrere Auftragsherkünfte wählen. Die gewählten Auftragsherkünfte müssen an der Variante aktiviert sein, damit der Artikel exportiert wird.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Formateinstellungen
        </td>
    </tr>
    <tr>
        <td>
            Auftragsherkunft
        </td>
        <td>
            Die Auftragsherkunft wählen, die beim Auftragsimport zugeordnet werden soll.
        </td>        
    </tr>
    <tr>
        <td>
            Angebotspreis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
    <tr>
        <td>
            MwSt.-Hinweis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
</table>


## 3 Übersicht der verfügbaren Spalten

<table>
    <tr>
        <th>
            Spaltenbezeichnung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
		<td>
			Produkt ID
		</td>
		<td>
			<b>Inhalt:</b> Die <b>Varianten-ID</b> der Variante.
		</td>        
	</tr>
	<tr>
		<td>
			Artikel Nr
		</td>
		<td>
			<b>Inhalt:</b> Das <b>Variantennummer</b> der Variante.
		</td>        
	</tr>
	<tr>
		<td>
			MPN
		</td>
		<td>
			<b>Inhalt:</b> Das <b>Modell</b> der Variante.
		</td>        
	</tr>
	<tr>
		<td>
			EAN
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Barcode</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Marke
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Name des Herstellers</b> des Artikels. Der <b>Externe Name</b> unter <b>Einstellungen » Artikel » Hersteller</b> wird bevorzugt, wenn vorhanden.
		</td>        
	</tr>
	<tr>
		<td>
			Produktname
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Artikelname</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Produktbeschreibung
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Beschreibung</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Preis inkl. MwSt.
		</td>
		<td>
			<b>Inhalt:</b> Hier steht der <b>Verkaufspreis</b>.
		</td>        
	</tr>
	<tr>
		<td>
			UVP
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Verkaufspreis</b> der Variante. Wenn der <b>UVP</b> in den Formateinstellungen aktiviert wurde und höher ist als der Verkaufspreis, wird dieser hier eingetragen.
		</td>        
	</tr>
	<tr>
		<td>
			Produkt-URL
		</td>
		<td>
			<b>Inhalt:</b> Der <b>URL-Pfad</b> des Artikels abhängig vom gewählten <b>Mandanten</b> in den Formateinstellungen.
		</td>        
	</tr>
	<tr>
		<td>
			Bild1
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Bild2
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Bild3
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Bild4
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Bild5
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Lieferkosten
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Versandkosten</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Auf Lager
		</td>
		<td>
			<b>Inhalt:</b> Gibt an, ob die Variante <b>Bestand</b> abhängig von <b>Lagerbestand</b> hat.
		</td>        
	</tr>
	<tr>
		<td>
			Lagerbestand
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Nettowarenbestand der Variante</b>. Bei Artikeln, die nicht auf den Nettowarenbestand beschränkt sind, wird <b>999</b> übertragen.
		</td>        
	</tr>
	<tr>
		<td>
			Lieferfrist
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Name der Artikelverfügbarkeit</b> unter <b>Einstellungen » Artikel » Artikelverfügbarkeit</b> oder die Übersetzung gemäß der Formateinstellung <b>Artikelverfügbarkeit überschreiben</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Kategorie 1
		</td>
		<td>
			<b>Inhalt:</b> Der Name der Kategorieebene 1.
		</td>        
	</tr>
	<tr>
		<td>
			Kategorie 2
		</td>
		<td>
			<b>Inhalt:</b> Der Name der Kategorieebene 2.
		</td>        
	</tr>
	<tr>
		<td>
			Kategorie 3
		</td>
		<td>
			<b>Inhalt:</b> Der Name der Kategorieebene 3.
		</td>        
	</tr>
	<tr>
		<td>
			Kategorie 4
		</td>
		<td>
			<b>Inhalt:</b> Der Name der Kategorieebene 4.
		</td>        
	</tr>
	<tr>
		<td>
			Kategorie 5
		</td>
		<td>
			<b>Inhalt:</b> Der Name der Kategorieebene 5.
		</td>        
	</tr>
	<tr>
		<td>
			Kategorie 6
		</td>
		<td>
			<b>Inhalt:</b> Der Name der Kategorieebene 6.
		</td>        
	</tr>
	<tr>
		<td>
			Farbe
		</td>
		<td>
			<b>Inhalt:</b> Der Wert eines Attributs, bei dem die Attributverknüpfung für <b>Amazon</b> mit <b>Color</b> gesetzt wurde. 
		</td>        
	</tr>
	<tr>
		<td>
			Größe
		</td>
		<td>
			<b>Inhalt:</b> Der Wert eines Attributs, bei dem die Attributverknüpfung für <b>Amazon</b> mit <b>Size</b> gesetzt wurde.
		</td>        
	</tr>
    <tr>
		<td>
			Gewicht
		</td>
		<td>
			<b>Inhalt:</b> Das <b>Gewicht</b> der Variante.
		</td>        
	</tr>
	<tr>
		<td>
			Grundpreis
		</td>
		<td>
			<b>Content:</b> The <b>base price information</b>. The format is "price / unit". (Example: 10,00 EUR / kilogram)
		</td>        
	</tr>
	<tr>
		<td>
			ID
		</td>
		<td>
			<b>Inhalt:</b> Die <b>Artikel-ID</b> der Variante.
		</td>        
	</tr>
</table>

## 4 Lizenz

Das gesamte Projekt unterliegt der GNU AFFERO GENERAL PUBLIC LICENSE – weitere Informationen finden Sie in der [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-beezup/blob/master/LICENSE.md).
