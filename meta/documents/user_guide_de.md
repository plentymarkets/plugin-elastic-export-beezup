
# User Guide für das ElasticExportBeezUp Plugin

<div class="container-toc"></div>

## 1 Bei BeezUP registrieren

BeezUP ist ein Tool zur Verwaltung und Optimierung der Präsentation Ihrer Artikel in Preisportalen, Marktplätzen und bei Affiliate-Diensten. Weitere Informationen zu diesem Dienst finden Sie auf der Handbuchseite [BeezUP](https://knowledge.plentymarkets.com/omni-channel/online-shop/webshop-einrichten/extras/beezup). Um das Plugin für BeezUP einzurichten, registrieren Sie sich zunächst als Händler.

## 2 Das Format BeezUp-Plugin in plentymarkets einrichten

Mit der Installation dieses Plugins erhalten Sie das Exportformat **BeezUp-Plugin**, mit dem Sie Daten über den elastischen Export zu BeezUP übertragen. Um dieses Format für den elastischen Export nutzen zu können, installieren Sie zunächst das Plugin **Elastic Export** aus dem plentyMarketplace, wenn noch nicht geschehen. 

Sobald beide Plugins in Ihrem System installiert sind, kann das Exportformat **BeezUp-Plugin** erstellt werden. Weitere Informationen finden Sie auf der Handbuchseite [Elastischer Export](https://knowledge.plentymarkets.com/basics/datenaustausch/elastischer-export).

Neues Exportformat erstellen:

1. Öffnen Sie das Menü **Daten » Elastischer Export**.
2. Klicken Sie auf **Neuer Export**.
3. Nehmen Sie die Einstellungen vor. Beachten Sie dazu die Erläuterungen in Tabelle 1.
4. **Speichern** Sie die Einstellungen.
→ Eine ID für das Exportformat **BeezUp-Plugin** wird vergeben und das Exportformat erscheint in der Übersicht **Exporte**.

In der folgenden Tabelle finden Sie Hinweise zu den einzelnen Formateinstellungen und empfohlenen Artikelfiltern für das Format **BeezUp-Plugin**.

| **Einstellung**                                     | **Erläuterung**|
| :---                                                | :--- |                                            
| **Einstellungen**                                   | |
| **Name**                                            | Name eingeben. Unter diesem Namen erscheint das Exportformat in der Übersicht im Tab **Exporte**. |
| **Typ**                                             | Typ **Artikel** aus der Dropdown-Liste wählen. |
| **Format**                                          | Das Format **BeezUp-Plugin** wählen. |
| **Limit**                                           | Zahl eingeben. Wenn mehr als 9999 Datensätze an BeezUp übertragen werden sollen, wird die Ausgabedatei für 24 Stunden nicht noch einmal neu generiert, um Ressourcen zu sparen. Wenn mehr als 9999 Datensätze benötigt werden, muss die Option **Cache-Datei generieren** aktiviert sein. | 
| **Bereitstellung**                                  | **URL** wählen. Mit dieser Option kann ein Token für die Authentifizierung generiert werden, damit ein externer Zugriff möglich ist. |
| **Dateiname**                                       | Der Dateiname muss auf **.csv** oder **.txt** enden, damit BeezUp.com die Datei erfolgreich importieren kann. |
| **Token, URL**                                      | Wenn Unter **Bereitstellung** die Option **URL** gewählt wurde, auf **Token generieren** klicken. Der Token wird dann automatisch eingetragen. Die URL wird automatisch eingetragen, wenn unter **Token** der Token generiert wurde. |
| **Artikelfilter**                                   | |
| **Artikelfilter hinzufügen**                        | Artikelfilter aus der Dropdown-Liste wählen und auf **Hinzufügen** klicken. Standardmäßig sind keine Filter voreingestellt. Es ist möglich, alle Artikelfilter aus der Dropdown-Liste nacheinander hinzuzufügen.<br/> **Varianten** = **Alle übertragen** oder **Nur Hauptvarianten übertragen** wählen.<br/> **Märkte** = Einen, mehrere, oder **ALLE** Märkte wählen. Die Verfügbarkeit muss für alle hier gewählten Märkte am Artikel hinterlegt sein. Andernfalls findet kein Export statt.<br/> **Währung** = Währung wählen.<br/> **Kategorie** = Aktivieren, damit der Artikel mit Kategorieverknüpfung übertragen wird. Es werden nur Artikel übertragen, die dieser Kategorie angehören.<br/> **Bild** = Aktivieren, damit der Artikel mit Bild übertragen wird. Es werden nur Artikel mit Bildern übertragen.<br/> **Mandant** = Mandant wählen.<br/> **Bestand** = Wählen, welche Bestände exportiert werden sollen.<br/> **Markierung 1-2** = Markierung wählen.<br/> **Hersteller** = Einen, mehrere, oder **ALLE** Hersteller wählen.<br/> **Aktiv** = **Aktiv** wählen. Nur aktive Varianten werden übertragen. |
| **Formateinstellungen**                             | |
| **Produkt-URL**                                     | Wählen, ob die URL des Artikels oder der Variante an BeezUP übertragen wird. Varianten URLs können nur in Kombination mit dem Ceres Webshop übertragen werden. |
| **Mandant**                                         | Mandant wählen. Diese Einstellung wird für den URL-Aufbau verwendet. |
| **URL-Parameter**                                   | Suffix für die Produkt-URL eingeben, wenn dies für den Export erforderlich ist. Die Produkt-URL wird dann um die eingegebene Zeichenkette erweitert, wenn weiter oben die Option **übertragen** für die Produkt-URL aktiviert wurde. |
| **Auftragsherkunft**                                | Aus der Dropdown-Liste die Auftragsherkunft wählen, die beim Auftragsimport zugeordnet werden soll. |
| **Marktplatzkonto**                                 | Marktplatzkonto aus der Dropdown-Liste wählen. Die Produkt-URL wird um die gewählte Auftragsherkunft erweitert, damit die Verkäufe später analysiert werden können. |
| **Sprache**                                         | Sprache aus der Dropdown-Liste wählen. |
| **Artikelname**                                     | **Name 1**, **Name 2**, oder **Name 3** wählen. Die Namen sind im Tab **Texte** eines Artikels gespeichert.<br/> Im Feld **Maximale Zeichenlänge (def. Text)** optional eine Zahl eingeben, wenn BeezUp eine Begrenzung der Länge des Artikelnamen beim Export vorgibt. |
| **Vorschautext**                                    | Wählen, ob und welcher Text als Vorschautext übertragen werden soll.<br/> Im Feld **Maximale Zeichenlänge (def. Text)** optional eine Zahl eingeben, wenn BeezUp eine Begrenzung der Länge des Vorschautextes beim Export vorgibt.<br/> Option **HTML-Tags entfernen** aktivieren, damit die HTML-Tags beim Export entfernt werden.<br/> Im Feld **Erlaubte HTML-Tags, kommagetrennt (def. Text)** optional die HTML-Tags eingeben, die beim Export erlaubt sind. Wenn mehrere Tags eingegeben werden, mit Komma trennen. |
| **Beschreibung**                                    | Wählen, welcher Text als Beschreibungstext übertragen werden soll.<br/> Im Feld **Maximale Zeichenlänge (def. Text)** optional eine Zahl eingeben, wenn BeezUp eine Begrenzung der Länge der Beschreibung beim Export vorgibt.<br/> Option **HTML-Tags  entfernen** aktivieren, damit die HTML-Tags beim Export entfernt werden.<b/> Im Feld **Erlaubte HTML-Tags, kommagetrennt (def. Text)** optional die HTML-Tags eingeben, die beim Export erlaubt sind. Wenn mehrere Tags eingegeben werden, mit Komma trennen. |
| **Zielland**                                        | Zielland aus der Dropdown-Liste wählen. |
| **Barcode**                                         | ASIN, ISBN, oder eine EAN aus der Dropdown-Liste wählen. Der gewählte Barcode muss mit der oben gewählten Auftragsherkunft verknüpft sein. Andernfalls wird der Barcode nicht exportiert. |
| **Bild**                                            | **Position 0** oder **Erstes Bild** wählen, um dieses Bild zu exportieren.<br/> **Position 0** = Ein Bild mit der Position 0 wird übertragen.<br/> **Erstes Bild** = Das erste Bidl wird übertragen. |
| **Bildposition des Energieetiketts**                | Diese Option ist für dieses Format nicht relevant. |
| **Bestandspuffer**                                  | Der Bestandspuffer für Varianten mit der Beschränkung auf den Netto-Warenbestand. |
| **Bestand für Varianten ohne Bestandsbeschränkung** | Der Bestand für Varianten ohne Bestandsbeschränkung.|
| **Bestand für Varianten ohne Bestandsführung**      | Der Bestand für Varianten ohne Bestandsführung. |
| **Währung live umrechnen**                          | Aktivieren, damit der Preis je nach eingestelltem Lieferland in die Währung des Lieferlandes umgerechnet wird. Der Preis muss für die entsprechende Währung freigegeben sein. |
| **Verkaufspreis**                                   | Brutto- oder Nettopreis aus der Dropdown-Liste wählen. |
| **Angebotspreis**                                   | Diese Option ist für dieses Format nicht relevant. |
| **UVP**                                             | Aktivieren, um den UVP zu übertragen. |
| **Versandkosten**                                   | Aktivieren, damit die Versandkosten aus der Konfiguration übernommen werden. Wenn die Option aktiviert ist, stehen in den beiden Dropdown-Listen Optionen für die Konfiguration und die Zahlungsart zur Verfügung.<br/> Option **Pauschale Versandkosten übertragen** aktivieren, damit die pauschalen Versandkosten übertragen werden. Wenn diese Option aktiviert ist, muss im Feld darunter ein Betrag eingegeben werden. |
| **MwSt.-Hinweis**                                   | Diese Option ist für dieses Format nicht relevant. |
| **Artikelverfügbarkeit überschreiben**              | Option **überschreiben** aktivieren und in die Felder **1** bis **10**, die die ID der Verfügbarkeit darstellen, Artikelverfügbarkeiten eintragen. Somit werden die Artikelverfügbarkeiten, die im Menü **System » Artikel » Verfügbarkeit** eingestellt wurden, überschrieben. |

_Tab. 1: Einstellungen für das Datenformat **BeezUp-Plugin**_

## 3 Verfügbare Spalten der Exportdatei

| **Spaltenbezeichnung** | **Erläuterung** |
| :---                   | :--- |
| Produkt ID             | Die **Varianten-ID** der Variante. |
| Artikel Nr             | Die Variantennummer der Variante. |
| MPN                    | Das **Modell** der Variante. |
| EAN                    | Entsprechend der Formateinstellung **Barcode**. |
| Marke                  | Der **Name des Herstellers** des Artikels. Der **Externe Name** unter **Einstellungen » Artikel » Hersteller** wird bevorzugt, wenn vorhanden. |
| Produktname            | Entsprechend der Formateinstellung **Artikelname**. |
| Produktbeschreibung    | Entsprechend der Formateinstellung **Beschreibung**. |
| Preis inkl. MwSt.      | Hier steht der **Verkaufspreis**. |
| UVP inkl. MwSt.        | Der **Verkaufspreis** der Variante. Wenn der **UVP** in den Formateinstellungen aktiviert wurde und höher ist als der Verkaufspreis, wird dieser hier eingetragen. |
| Produkt-URL            | Der **URL-Pfad** des Artikels abhängig vom gewählten **Mandanten** in den Formateinstellungen. |
| Bild-URL               | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild-URL2              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild-URL3              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild-URL4              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild-URL5              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Lieferkosten           | Entsprechend der Formateinstellung **Versandkosten**. |
| Auf Lager              | Gibt an, ob die Variante **Bestand** abhängig von **Lagerbestand** hat. |
| Lagerbestand           | Der **Netto-Warenbestand der Variante**. Bei Artikeln, die nicht auf den Netto-Warenbestand beschränkt sind, wird **999** übertragen. |
| Lieferfrist            | Der **Name der Artikelverfügbarkeit** unter **Einstellungen » Artikel » Artikelverfügbarkeit** oder die Übersetzung gemäß der Formateinstellung **Artikelverfügbarkeit überschreiben**. |
| Kategorie 1            | Der Name der Kategorieebene 1. |
| Kategorie 2            | Der Name der Kategorieebene 2. |
| Kategorie 3            | Der Name der Kategorieebene 3. |
| Kategorie 4            | Der Name der Kategorieebene 4. |
| Kategorie 5            | Der Name der Kategorieebene 5. |
| Kategorie 6            | Der Name der Kategorieebene 6. |
| Farbe                  | Der Wert eines Attributs, bei dem die Attributverknüpfung für **Amazon** mit **Color** gesetzt wurde. |
| Größe                  | Der Wert eines Attributs, bei dem die Attributverknüpfung für **Amazon** mit **Size** gesetzt wurde. |
| Gewicht                | Das **Gewicht** der Variante. |
| Grundpreis             | Die **Grundpreisinformation** im Format "Preis / Einheit". (Beispiel: 10,00 EUR / Kilogramm) |
| ID                     | Die **Artikel-ID** der Variante. |

> Wenn Artikel, die Sie zu BeezUP exportieren möchten, mit Merkmalen verknüpft sind, wird die Exportdatei automatisch um zusätzliche Spalten für diese Merkmale erweitert. Die Spalten in der Exportdatei haben den _Webshop_-Namen des Merkmals.

## 4 Lizenz

Das gesamte Projekt unterliegt der GNU AFFERO GENERAL PUBLIC LICENSE – weitere Informationen finden Sie in der [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-beezup/blob/master/LICENSE.md).
