# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

["Sports", "Music"].each do |r|
  EventType.create(name: r)
end

["English", "Hindi", "Telugu"].each do |r|
  EventLanguage.create(name: r)
end

@type=EventType.find(1)
@lan=EventLanguage.find(1)
results = Geocoder.search('Hyderabad, Andhra Pradesh, India') 

event = Event.create(name:'naresh', start_at: Date.today-1.days, end_at: Date.today-1.days, city: results[0].city , event_type_id: @type.id, event_language_id: @lan.id, latitude: results[0].latitude, longitude: results[0].longitude, state: results[0].state, country: results[0].country, zipcode: results[0].postal_code,start_minute: 00 ,start_hour: 9 ,start_meridiem: 'AM',end_minute: 00 ,end_hour: 11,end_meridiem: 'AM', address: 'Hyderabad, Andhra Pradesh, India',content:'hii',venue:'hyd' )




