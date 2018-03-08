
# User Guide für das ElasticExportBeezUp Plugin

<div class="container-toc"></div>

## 1 Bei BeezUp registrieren

BeezUP ist ein Tool zur Verwaltung und Optimierung der Präsentation Ihrer Artikel in Preisportalen, Marktplätzen und bei Affiliate-Diensten. Weitere Informationen zu diesem Dienst finden Sie auf der Handbuchseite [BeezUp einrichten](https://www.plentymarkets.eu/handbuch/mandant-shop/standard/externe-dienste/beezup/). Um das Plugin für BeezUp einzurichten, registrieren Sie sich zunächst als Händler.

## 2 Das Format BeezUp-Plugin in plentymarkets einrichten

Um dieses Format nutzen zu können, benötigen Sie das Plugin Elastic Export.

Auf der Handbuchseite [Daten exportieren](https://www.plentymarkets.eu/handbuch/datenaustausch/daten-exportieren/#4) werden die einzelnen Formateinstellungen beschrieben.

In der folgenden Tabelle finden Sie Hinweise zu den Einstellungen, Formateinstellungen und empfohlenen Artikelfiltern für das Format **BeezUp-Plugin**.

| **Einstellung**                                 | **Erläuterung**|
| :---                                            | :--- |                                            
| **Einstellungen**                               |
| Format                                          | Das Format **BeezUp-Plugin** wählen. |
| Bereitstellung                                  | Die Bereitstellung **URL** wählen. |
| Dateiname                                       | Der Dateiname muss auf **.csv** oder **.txt** enden, damit BeezUp.com die Datei erfolgreich importieren kann. |
| **Artikelfilter**                               |
| Aktiv                                           | **Aktiv** wählen. |
| Märkte                                          | Eine oder mehrere Auftragsherkünfte wählen. Die gewählten Auftragsherkünfte müssen an der Variante aktiviert sein, damit der Artikel exportiert wird. |
| **Formateinstellungen**                         |
| Auftragsherkunft                                | Die Auftragsherkunft wählen, die beim Auftragsimport zugeordnet werden soll. |
| Bestandspuffer                                  | Der Bestandspuffer für Varianten mit der Beschränkung auf den Netto-Warenbestand. |
| Bestand für Varianten ohne Bestandsbeschränkung | Der Bestand für Varianten ohne Bestandsbeschränkung.|
| Bestand für Varianten ohne Bestandsführung      | Der Bestand für Varianten ohne Bestandsführung. |
| Angebotspreis                                   | Diese Option ist für dieses Format nicht relevant. |
| MwSt.-Hinweis                                   | Diese Option ist für dieses Format nicht relevant. |

## 3 Übersicht der verfügbaren Spalten

| **Spaltenbezeichnung** | **Erläuterung** |
| :---                   | :--- |
| Produkt ID             | **Inhalt**: Die **Varianten-ID** der Variante. |
| Artikel Nr             | **Inhalt**: Die Variantennummer der Variante. |
| MPN                    | **Inhalt**: Das **Modell** der Variante. |
| EAN                    | **Inhalt**: Entsprechend der Formateinstellung **Barcode**. |
| Marke                  | **Inhalt**: Der **Name des Herstellers** des Artikels. Der **Externe Name** unter **Einstellungen » Artikel » Hersteller** wird bevorzugt, wenn vorhanden. |
| Produktname            | **Inhalt**: Entsprechend der Formateinstellung **Artikelname**. |
| Produktbeschreibung    | **Inhalt**: Entsprechend der Formateinstellung **Beschreibung**. |
| Preis inkl. MwSt.      | **Inhalt**: Hier steht der **Verkaufspreis**. |
| UVP                    | **Inhalt**: Der **Verkaufspreis** der Variante. Wenn der **UVP** in den Formateinstellungen aktiviert wurde und höher ist als der Verkaufspreis, wird dieser hier eingetragen. |
| Produkt-URL            | **Inhalt**: Der **URL-Pfad** des Artikels abhängig vom gewählten **Mandanten** in den Formateinstellungen. |
| Bild1                  | **Inhalt**: URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild2                  | **Inhalt**: URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild3                  | **Inhalt**: URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild4                  | **Inhalt**: URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild5                  | **Inhalt**: URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Lieferkosten           | **Inhalt**: Entsprechend der Formateinstellung **Versandkosten**. |
| Auf Lager              | **Inhalt**: Gibt an, ob die Variante **Bestand** abhängig von **Lagerbestand** hat. |
| Lagerbestand           | **Inhalt**: Der **Netto-Warenbestand der Variante**. Bei Artikeln, die nicht auf den Netto-Warenbestand beschränkt sind, wird **999** übertragen. |
| Lieferfrist            | **Inhalt**: Der **Name der Artikelverfügbarkeit** unter **Einstellungen » Artikel » Artikelverfügbarkeit** oder die Übersetzung gemäß der Formateinstellung **Artikelverfügbarkeit überschreiben**. |
| Kategorie 1            | **Inhalt**: Der Name der Kategorieebene 1. |
| Kategorie 2            | **Inhalt**: Der Name der Kategorieebene 2. |
| Kategorie 3            | **Inhalt**: Der Name der Kategorieebene 3. |
| Kategorie 4            | **Inhalt**: Der Name der Kategorieebene 4. |
| Kategorie 5            | **Inhalt**: Der Name der Kategorieebene 5. |
| Kategorie 6            | **Inhalt**: Der Name der Kategorieebene 6. |
| Farbe                  | **Inhalt**: Der Wert eines Attributs, bei dem die Attributverknüpfung für **Amazon** mit **Color** gesetzt wurde. |
| Größe                  | **Inhalt**: Der Wert eines Attributs, bei dem die Attributverknüpfung für **Amazon** mit **Size** gesetzt wurde. |
| Gewicht                | **Inhalt**: Das **Gewicht** der Variante. |
| Grundpreis             | **Inhalt**: Die **Grundpreisinformation** im Format "Preis / Einheit". (Beispiel: 10,00 EUR / Kilogramm) |
| ID                     | **Inhalt**: Die **Artikel-ID** der Variante. |

## 4 Lizenz

Das gesamte Projekt unterliegt der GNU AFFERO GENERAL PUBLIC LICENSE – weitere Informationen finden Sie in der [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-beezup/blob/master/LICENSE.md).
